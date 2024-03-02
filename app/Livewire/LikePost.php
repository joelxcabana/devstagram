<?php

namespace App\Livewire;

use Livewire\Component;

class LikePost extends Component
{
    // esta variable ya esta disponible en la vista de like-post para mostrar
    public $post;
    public $isLiked;
    public $likes;

    // cuando se monta el componente
    public function mount($post)
    {
        $this->isLiked = $post->checkLike(auth()->user());
        $this->likes = $post->likes->count();
    }

    public function like()
    {
        // le pone like
        if($this->post->checkLike(auth()->user())){
            $this->post->likes()->where('post_id', $this->post->id)->delete();
            $this->isLiked = false;
            $this->likes--;
        }else{
            //sacar el like
            $this->post->likes()->create([
                'user_id' => auth()->user()->id
            ]);
            $this->isLiked = true;
            $this->likes++;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
