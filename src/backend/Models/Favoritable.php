<?php

namespace App\Models;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function favorite()
    {
        $favorite = new Favorite(['user_id' => auth()->id()]);
        $this->favorites()->save($favorite);
    }

    public function unfavorite()
    {
        $this->favorites()->where('user_id', auth()->id())->delete();
    }

    public function toggleFavorited()
    {
        $this->isFavorited() ? $this->unfavorite() : $this->favorite();
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public static function getFavorited()
    {
        return Favorite::where('user_id', auth()->id())->get()->map(function ($favorite) {
            return $favorite->favoritable;
        });
    }
}
