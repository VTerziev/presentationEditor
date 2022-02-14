<?php
  class File {
    // DB Stuff
    private $conn;
    private $table = 'Files';

    // Properties
    public $fileName;
    public $content;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get files
    public function read() {
      $query = 'SELECT * FROM Files';
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    // Get single file
    public function read_single() {
      $query = 'SELECT * FROM Files WHERE FileName = :fileName LIMIT 1';

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':fileName', $this->fileName);

      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->fileName = $row['FileName'];
      $this->content = $row['Content'];
    }

    // Create file
    public function create() {
      $query = 'INSERT INTO Files SET FileName = :fileName, Content = :content';
      $stmt = $this->conn->prepare($query);

      $stmt-> bindParam(':fileName', $this->fileName);
      $stmt-> bindParam(':content', $this->content);

      if($stmt->execute()) {
        return true;
      }

      printf("Error: $s.\n", $stmt->error);
      return false;
    }

    // Update file
    public function update() {
      $query = 'UPDATE Files SET Content = :content WHERE FileName = :fileName';
      $stmt = $this->conn->prepare($query);

      $this->content = htmlspecialchars(strip_tags($this->content));
      $this->fileName = htmlspecialchars(strip_tags($this->fileName));

      $stmt-> bindParam(':content', $this->content);
      $stmt-> bindParam(':fileName', $this->fileName);

      if($stmt->execute()) {
        return true;
      }
      printf("Error: $s.\n", $stmt->error);
      return false;
    }

  // // Delete file
  // public function delete() {
  //   // Create query
  //   $query = 'DELETE FROM ' . $this->table . ' WHERE fileName = :fileName';

  //   // Prepare Statement
  //   $stmt = $this->conn->prepare($query);

  //   // clean data
  //   $this->fileName = htmlspecialchars(strip_tags($this->fileName));

  //   // Bind Data
  //   $stmt-> bindParam(':fileName', $this->fileName);

  //   // Execute query
  //   if($stmt->execute()) {
  //     return true;
  //   }

  //   // Print error if something goes wrong
  //   printf("Error: $s.\n", $stmt->error);

  //   return false;
  //   }

    public function split_slides() {
      // "/\r\n|\n|\r/"
      $lines = preg_split("/\n/", $this->content);

      $slides = array();
      $curr_slide = "";
      foreach($lines as $line) {
        if (substr($line, 0, 7) === "= slide") {
          if ($curr_slide != "") {
            array_push($slides, $curr_slide);
          }
          $curr_slide = $line;
        } else {
          $curr_slide = $curr_slide . $line;
        }
      }
      if ($curr_slide != "") {
        array_push($slides, $curr_slide);
      }
      return $slides;
    }

    public function merge_slides($slides) {
      $text = "";
      foreach($slides as $slide) {
        $text  = $text . "\n" . $slide;
      }
      $this->content = $text;
    }
  }
