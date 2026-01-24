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
        // Permission check
        if (!auth()->user()->hasRole('admin')) {
            $this->errorAlert('Error', 'You do not have permission to delete pages.');
            return;
        }

        Page::find($id)->delete();
        $this->successAlert('Deleted', 'Page deleted successfully.');
    }
}
