<?php
include 'config.php';  // Include configuration file

/**
 * Connects to the database using PDO and returns the connection object.
 *
 * @return PDO|null
 */
function connectDatabase(): ?PDO {
    try {
        // Connection options
        $connectionOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch rows as associative arrays
        ];

        // Create a new PDO instance
        return new PDO("sqlsrv:server=" . DB_SERVER . ";Database=" . DB_NAME, DB_USER, DB_PASSWORD, $connectionOptions);

    } catch (PDOException $e) {
        // Log any errors
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}
