<?php
  class File {
    // DB Stuff
    private $conn;
    private $table = 'Files';

    // Properties
    public $fileName;
    public $slides;

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

  //   // Get single file
  // public function read_single() {
  //   // Create query
  //   $query = 'SELECT
  //         fileName,
  //         slides
  //       FROM
  //         ' . $this->table . '
  //     WHERE fileName = ?
  //     LIMIT 0,1';

  //     //Prepare statement
  //     $stmt = $this->conn->prepare($query);

  //     // Bind fileName
  //     $stmt->bindParam(1, $this->fileName);

  //     // Execute query
  //     $stmt->execute();

  //     $row = $stmt->fetch(PDO::FETCH_ASSOC);

  //     // set properties
  //     $this->fileName = $row['fileName'];
  //     $this->slides = $row['slides'];
  // }

  // Create file
  public function create() {
    // Create Query
    $query = 'INSERT INTO Files SET FileName = :fileName, Slides = :slides';
    $stmt = $this->conn->prepare($query);

    $stmt-> bindParam(':fileName', $this->fileName);
    $stmt-> bindParam(':slides', $this->slides);

    if($stmt->execute()) {
      return true;
    }

    printf("Error: $s.\n", $stmt->error);
    return false;
  }

  // Update file
  public function update() {
    $query = 'UPDATE Files SET Slides = :slides WHERE FileName = :fileName';
    $stmt = $this->conn->prepare($query);

    $this->slides = htmlspecialchars(strip_tags($this->slides));
    $this->fileName = htmlspecialchars(strip_tags($this->fileName));

    $stmt-> bindParam(':slides', $this->slides);
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
  }
