<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Page;
use App\Traits\AlertTrait;

class Pages extends Component
{
    use WithPagination, AlertTrait;

    public $search;

    public function render()
    {
        $pages = Page::where('title', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.pages', [
            'pages' => $pages
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'All Pages']);
    }

    public function delete($id)
    {
        try {
            // Permission check
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission to delete pages.');
                return;
            }

            $page = Page::find($id);
            if ($page) {
                $this->deleteId = $id;
                $message = "<strong>Delete Page: \"{$page->title}\"?</strong><br><small class='text-muted'>This action cannot be undone.</small>";
                $this->dispatch('swal:confirm-delete', [
                    'title' => 'Delete Page',
                    'message' => $message,
                    'confirmCallback' => 'confirmDeletePage'
                ]);
            }
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong.');
        }
    }

    public function confirmDeletePage()
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission to delete pages.');
                return;
            }

            if (!$this->deleteId) return;
            
            Page::find($this->deleteId)->delete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Page deleted successfully.');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting page.');
            $this->deleteId = null;
        }
    }
}
