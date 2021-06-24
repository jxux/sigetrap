<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\System\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component{

    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search;

    public function updatingSearch(){
        $this ->resetPage();
    }
    
    public function render(){

        $users = User::where('name', 'like' ,'%'.$this->search.'%')
                        ->orwhere('email', 'like' ,'%'.$this->search.'%')
                        ->paginate();
        return view('livewire.admin.users.index', compact('users'));

    }
}
