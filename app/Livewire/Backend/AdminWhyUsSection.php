<?php

namespace App\Livewire\Backend;

use App\Models\FeatureCard;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminWhyUsSection extends Component
{
    use WithFileUploads;

    // Header Settings
    public array $state = [
        'why_badge' => '✦ Why Choose Us',
        'why_title' => 'We offer more than just<br/><span class="text-yellow-400">an education</span><span class="text-yellow-400">.</span>',
    ];

    // Card Form
    public $cardId;
    public $cardTitle;
    public $cardDesc;
    public $cardIcon;
    public $cardImgFile;
    public $cardSortOrder = 0;
    
    public $isEditing = false;

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->state['why_badge'] = Setting::get('why_badge', $this->state['why_badge']);
        $this->state['why_title'] = Setting::get('why_title', $this->state['why_title']);
    }

    public function saveSettings()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        
        $this->validate([
            'state.why_badge' => 'nullable|string|max:255',
            'state.why_title' => 'nullable|string|max:255',
        ]);

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'Header settings updated successfully.');
    }

    public function saveCard()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        
        $this->validate([
            'cardTitle' => 'required|string|max:255',
            'cardDesc'  => 'required|string',
            'cardIcon'  => 'nullable|string|max:50',
            'cardImgFile' => 'nullable|image|max:2048',
            'cardSortOrder' => 'nullable|integer',
        ]);

        $card = $this->isEditing ? FeatureCard::findOrFail($this->cardId) : new FeatureCard();

        $card->title = $this->cardTitle;
        $card->description = $this->cardDesc;
        $card->icon = $this->cardIcon;
        $card->sort_order = $this->cardSortOrder ?: 0;

        if ($this->cardImgFile) {
            // Delete old image if exists
            if ($this->isEditing && $card->image_path) {
                Storage::disk('public')->delete($card->image_path);
            }
            $card->image_path = $this->cardImgFile->store('feature_cards', 'public');
        }

        $card->save();

        $this->resetCardForm();
        session()->flash('card_success', $this->isEditing ? 'Card updated successfully.' : 'Card added successfully.');
    }

    public function editCard($id)
    {
        $card = FeatureCard::findOrFail($id);
        $this->cardId = $card->id;
        $this->cardTitle = $card->title;
        $this->cardDesc = $card->description;
        $this->cardIcon = $card->icon;
        $this->cardSortOrder = $card->sort_order;
        $this->isEditing = true;
    }

    public function deleteCard($id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $card = FeatureCard::findOrFail($id);
        if ($card->image_path) {
            Storage::disk('public')->delete($card->image_path);
        }
        $card->delete();
        session()->flash('card_success', 'Card deleted successfully.');
        if ($this->cardId == $id) {
            $this->resetCardForm();
        }
    }

    public function cancelEdit()
    {
        $this->resetCardForm();
    }

    private function resetCardForm()
    {
        $this->cardId = null;
        $this->cardTitle = '';
        $this->cardDesc = '';
        $this->cardIcon = '';
        $this->cardImgFile = null;
        $this->cardSortOrder = 0;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $cards = FeatureCard::orderBy('sort_order')->get();
        return view('livewire.pages.backend.admin-why-us-section', ['cards' => $cards])->layout('layouts.app');
    }
}
