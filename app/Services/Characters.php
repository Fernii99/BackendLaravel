<?php

class Character
{
    public $data;      // Array to hold character objects
    public $status;    // Boolean status
    public $message;


    // Constructor
    public function __construct($status, $message)
    {
        $this->status = $status;       // Set status
        $this->message = $message;     // Set message
        $this->data = [];              // Initialize as an empty array
    }


    public function addCharacter($name, $gender, $status)
    {
        $this->data[] = [              // Append character data to the data array
            'name' => $name,
            'gender' => $gender,
            'status' => $status,
        ];
    }
    // Method
    public function getResponse()
    {
        // Structure the response
        $response = [
            'data' => $this->data,      // Character array
            'status' => $this->status,  // Status
            'message' => $this->message // Message
        ];

        // Return as JSON
        return response()->json($response);
    }
}

