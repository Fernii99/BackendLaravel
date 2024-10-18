<?php

namespace App\Services;

class Character
{
    public $data;
    public $status;
    public $message;


    // Constructor
    public function __construct()
    {
        $this->data = [];              // Initialize as an empty array
    }


    public function addCharacter($name, $gender, $image)
    {
        $this->data[] = [              // Append character data to the data array
            'name' => $name,
            'gender' => $gender,
            'image' => $image,

        ];
    }
    // Method
    public function getResponse()
    {
        // Structure the response
        $response = [
            'data' => $this->data,      // Character array
        ];

        // Return as JSON
        return response()->json($response);
    }
}

