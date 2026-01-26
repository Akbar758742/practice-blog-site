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
    public $deleteId = null;
    public $showTrashed = false;

    public function render()
    {
        $query = Page::where('title', 'like', '%' . $this->search . '%');

        // Show trashed or active pages
        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get trash count
        $trashCount = Page::onlyTrashed()->count();

        return view('livewire.admin.pages', [
            'pages' => $pages,
            'trashCount' => $trashCount
        ])->layout('backend.layout.pages-layout', ['pageTitle' => $this->showTrashed ? 'Trashed Pages' : 'All Pages']);
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->resetPage();
    }

    public function restore($id)
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission.');
                return;
            }
            $page = Page::onlyTrashed()->findOrFail($id);
            $page->restore();
            $this->successAlert('Restored', 'Page restored successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Could not restore page.');
        }
    }

    public function forceDelete($id)
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission.');
                return;
            }
            $this->deleteId = $id;
            $page = Page::onlyTrashed()->findOrFail($id);
            $message = "<strong>Permanently delete: \"{$page->title}\"?</strong><br><small class='text-danger'>This will permanently remove the page and cannot be recovered!</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Permanent Delete',
                'message' => $message,
                'confirmCallback' => 'confirmForceDelete'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Page not found.');
        }
    }

    public function confirmForceDelete()
    {
        try {
            if (!$this->deleteId) return;

            $page = Page::onlyTrashed()->findOrFail($this->deleteId);
            $page->forceDelete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Page permanently deleted!');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not permanently delete page.');
        }
    }

    public function delete($id)
    {
        try {
            // Permission check
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission to delete pages.');
                return;
            }

            $page = Page::findOrFail($id);

            $this->deleteId = $id;
            $message = "<strong>Delete Page: \"{$page->title}\"?</strong><br><small class='text-muted'>This action cannot be undone.</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Delete Page',
                'message' => $message,
                'confirmCallback' => 'confirmDeletePage'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Page not found.');
        }
    }

    public function confirmDeletePage()
    {
        try {
            if (!auth()->user()->hasRole('admin')) {
                $this->errorAlert('Error', 'You do not have permission to delete pages.');
                return;
            }

            if (!$this->deleteId) {
                $this->errorAlert('Error', 'No page selected for deletion.');
                return;
            }

            $page = Page::findOrFail($this->deleteId);
            $page->delete(); // Use soft delete
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Page deleted successfully.');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not delete page: ' . $e->getMessage());
        }
    }
}
