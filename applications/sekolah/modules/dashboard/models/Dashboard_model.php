<?php

class Dashboard_model extends Model {

    public function get_welcome_message() {
        // In a real application, this might come from the database
        return "Welcome to the school application dashboard!";
    }

}
