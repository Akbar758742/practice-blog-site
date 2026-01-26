<?php

namespace App\Traits;

trait AlertTrait
{
    public function successAlert($title, $message)
    {
        $this->dispatch('swal:success', [
            'title' => $title,
            'message' => $message
        ]);
    }

    public function errorAlert($title, $message)
    {
        $this->dispatch('swal:error', [
            'title' => $title,
            'message' => $message
        ]);
    }

    public function warningAlert($title, $message)
    {
        $this->dispatch('swal:warning', [
            'title' => $title,
            'message' => $message
        ]);
    }

    public function confirmDeleteAlert($title, $message, $confirmCallback, $cancelCallback = null)
    {
        $this->dispatch('swal:confirm-delete', [
            'title' => $title,
            'message' => $message,
            'confirmCallback' => $confirmCallback,
            'cancelCallback' => $cancelCallback
        ]);
    }
}
