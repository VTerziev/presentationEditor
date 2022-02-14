var fs = require('fs');
var { execSync } = require('child_process');
const express = require('express');
var ensure = moduleName => {
	var folders = ['.', '..'].map(e => `${e}/node_modules/${moduleName}`);
	if (!folders.some(fs.existsSync)) {
		console.log(`Module ${moduleName} not installed. Installing...`);
		try {
			execSync(`npm install ${moduleName}`);
		} catch (e) {
			console.log(`Failed. Run 'npm install ${moduleName}' and try again`);
			process.exit(1);
		}
	}
	return require(moduleName);
};
var util = require('util');
var slm = ensure('slm');

var escape = html => html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

var lineReplace = line =>
	line.replace(/`(.*?)`/g, '<code>$1</code>')
		// [github:user/repo]
		.replace(/\[github:([^\]]*?)\]/g, '[$1](https://github.com/$1)')
		// [text](url)
		.replace(/\[([^\]]+)\]\((\S+?)\)/g, '<a href="$2">$1</a>')


var features = {
	list: ({ indent, lines }) => indent + 'ul\n' + lines.filter(e => e.trim()).map(
		line => indent + '  li.action ' + lineReplace(line).trimLeft()
	).join('\n'),

	example: ({ indent, lines }) => {
		var lang = (lines[0].match(/^\s*\[lang:(\w+)\]\s*$/) || [])[1];
		if (lang) {
			lines = lines.slice(1);
		}
		var firstLine = indent + 'pre.highlight' + (lang ? '.' + lang : '') + '\n';
		return firstLine + indent + '  |\n' + lines.map(
			line => '  ' + escape(line)
		).join('\n');
	}
};
var featureKeys = Object.keys(features).map(e => e + ':');

var render = lecture => {
	var slidesMeta = [];
	var start = 0;
	var slides = lecture.split('\n= slide ').filter(e => e).map((e, i) => {
		var lines = e.trimRight().split('\n');
		var titles = (lines.shift().replace(/do\w*$/, '').match(/'(?:\\.|[^\\'])*'/g) || []).map(e => e.slice(1, -1));

		slidesMeta.push({
			id: i + 2,
			titles,
			start,
			end: (start += lines.length + 1)
		});

		var text = '';

		var feature;
		lines.forEach((line, i) => {
			var indent = line.match(/^\s*/g)[0];
			if (feature) {
				if (line.trim() && indent.length <= feature.indent.length) {
					text += features[feature.name](feature);
					feature = null;
				} else {
					feature.lines.push(line);
				}
			} else {
				if (featureKeys.includes(line.trim())) {
					feature = {
						name: line.trim().slice(0, -1),
						lines: [],
						indent
					};
				} else {
					text += line + '\n';
				}
			}
		});
		if (feature) {
			text += features[feature.name](feature);
		}
		return `section\n` + titles.map(e => `  h1 ${escape(e)}\n`).join('') + text; //lines.join('\n');
	});
	return slides.map((slide, i) => {
		try {
			return slm.render(slide);
		} catch (e) {
			onError(e, slidesMeta[i]);
		}
	}).join('\n');
};

var error;
function onError(e, meta) {
	console.log(`\nError on slide ${meta.id} (#${meta.start} - #${meta.end}) - ${meta.titles[0] || ''}`);
	var message;
	if (e.message == 'Invalid left-hand side in assignment') {
		message = 'ParseError: Unescaped = or ==';
	} else {
		message = e.stack.replace(/evalmachine.*\n/, '').replace(/\s*at createScript.*/s, '');
		message = message.split('\n').map(e => '    ' + e).join('\n');
	}
	console.error(message);
	error = e;
};

const app = express();
app.use(express.urlencoded()); // Parse URL-encoded bodies (as sent by HTML forms)
app.use(express.json()); // Parse JSON bodies (as sent by API clients)
app.post('/', (req, res) => {
	// console.log(JSON.stringify(req.body));

	// let filename = "CSS-2";

	var layout = fs.readFileSync('./lectures/layout.slim', { encoding: 'utf8' });
	// var actual = fs.readFileSync(`./lectures/${filename}.slim`, { encoding: 'utf8' });

	console.log(`\n\nProcessing body:\n${req.body.code}\n`);

	var actualHTML = render(req.body.code);
	if (!error) {
		var wholeHTML = slm.render(layout, { slides_html: actualHTML, title: req.body.presentation });

		// fs.writeFileSync(`./html/${filename}.html`, wholeHTML);
		// console.log(wholeHTML);

		console.log('Succes.');
	}

	// res.writeHead(200, {'Content-Type': 'text/plain'});
	res.writeHead(200, {'Access-Control-Allow-Origin': '*'});
	res.write(wholeHTML);
	res.end();
})

app.listen(1337, () => {
  console.log(`Server listening on port 1337`);
})

// var http = require('http');
// http.createServer(function (req, res) {
// 	let filename = "CSS-2";

// 	var layout = fs.readFileSync('./lectures/layout.slim', { encoding: 'utf8' });
// 	var actual = fs.readFileSync(`./lectures/${filename}.slim`, { encoding: 'utf8' });

// 	var actualHTML = render(actual);
// 	if (!error) {
// 		var wholeHTML = slm.render(layout, { slides_html: actualHTML, title: filename });

// 		fs.writeFileSync(`./html/${filename}.html`, wholeHTML);
// 		// console.log(wholeHTML);

// 		console.log(`\n Done: html/${filename}.html`);
// 	}

// 	// res.writeHead(200, {'Content-Type': 'text/plain'});
// 	res.writeHead(200, {'Access-Control-Allow-Origin': '*'});
// 	res.write(wholeHTML);
// 	res.end();
// }).listen(1337, "127.0.0.1");
// console.log('Server running at http://127.0.0.1:1337/');
