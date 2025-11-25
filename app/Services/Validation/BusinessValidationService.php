<?php

namespace App\Services\Validation;

use App\Models\Container;
use App\Models\ActiveInventory;
use App\Models\Terminal;
use App\Services\ISO6346Validator;

class BusinessValidationService
{
    /**
     * Validate container number format and check digit
     */
    public function validateContainerNumber(string $containerNumber): array
    {
        $errors = [];

        // Basic format check: 4 letters + 7 digits = 11 characters
        if (!preg_match('/^[A-Z]{4}\d{7}$/', $containerNumber)) {
            $errors[] = 'Container number must be 11 characters: 4 letters followed by 7 digits (e.g., ABCU1234567)';
        } else {
            // Validate the ISO 6346 check digit
            if (!ISO6346Validator::validateContainerNumber($containerNumber)) {
                $errors[] = 'Invalid check digit for container number. Please verify the container number is correct.';
            }
        }

        return $errors;
    }

    /**
     * Validate that a container is not already in active inventory at the specified terminal
     */
    public function validateContainerTerminalAvailability(string $containerNumber, int $terminalId): array
    {
        $errors = [];

        $container = Container::where('number', $containerNumber)->first();
        if (!$container) {
            $errors[] = 'Container number does not exist in the system.';
            return $errors;
        }

        // Check if container is already in active inventory at this terminal
        $existingInventory = ActiveInventory::where('container_id', $container->id)
                                           ->where('terminal_id', $terminalId)
                                           ->first();

        if ($existingInventory) {
            $errors[] = 'Container is already registered in active inventory at this terminal.';
        }

        return $errors;
    }

    /**
     * Validate terminal existence
     */
    public function validateTerminal(int $terminalId): array
    {
        $errors = [];

        $terminal = Terminal::find($terminalId);
        if (!$terminal) {
            $errors[] = 'Selected terminal does not exist.';
        } elseif (!$terminal->is_active) {
            $errors[] = 'Selected terminal is inactive.';
        }

        return $errors;
    }

    /**
     * Validate that a container is in active inventory at the specified terminal (for gate out, rail out)
     */
    public function validateContainerInTerminalInventory(string $containerNumber, int $terminalId): array
    {
        $errors = [];

        $container = Container::where('number', $containerNumber)->first();
        if (!$container) {
            $errors[] = 'Container number does not exist in the system.';
            return $errors;
        }

        // Check if container is in active inventory at this terminal
        $inventory = ActiveInventory::where('container_id', $container->id)
                                   ->where('terminal_id', $terminalId)
                                   ->first();

        if (!$inventory) {
            $errors[] = 'Container is not currently in active inventory at this terminal.';
        }

        return $errors;
    }

    /**
     * Validate rail schedule
     */
    public function validateRailSchedule(int $scheduleId): array
    {
        $errors = [];
        
        $schedule = \App\Models\RailSchedule::find($scheduleId);
        if (!$schedule) {
            $errors[] = 'Selected rail schedule does not exist.';
        } elseif ($schedule->status === 'completed' || $schedule->status === 'cancelled') {
            $errors[] = 'Selected rail schedule is no longer active.';
        }

        return $errors;
    }
}