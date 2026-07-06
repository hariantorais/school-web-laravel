<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Institution;
use Illuminate\Support\Facades\Storage;

class InstitutionForm extends Form
{
    public ?Institution $institution = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $vision = '';

    #[Validate('nullable|string')]
    public string $mission = '';

    #[Validate('nullable|string|max:255')]
    public string $motto = '';

    #[Validate('nullable|string|max:255')]
    public string $tagline = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|string')]
    public string $history = '';

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $whatsapp = '';

    #[Validate('nullable|string|max:20')]
    public string $whatsapp_2 = '';

    #[Validate('nullable|url|max:255')]
    public string $facebook = '';

    #[Validate('nullable|url|max:255')]
    public string $instagram = '';

    #[Validate('nullable|url|max:255')]
    public string $youtube = '';

    #[Validate('nullable|url|max:255')]
    public string $twitter = '';

    #[Validate('nullable|url|max:255')]
    public string $tiktok = '';

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('nullable|string|max:255')]
    public string $meta_title = '';

    #[Validate('nullable|string')]
    public string $meta_description = '';

    #[Validate('nullable|string|max:255')]
    public string $meta_keywords = '';

    #[Validate('nullable|string|max:255')]
    public string $timezone = 'Asia/Jakarta';

    #[Validate('nullable|string|max:10')]
    public string $locale = 'id';

    // Hapus #[Validate] dari sini, validasi akan di-handle di method
    public $established_year = null;

    #[Validate('nullable|string|max:255')]
    public string $accreditation = '';

    #[Validate('nullable|image|max:2048')]
    public $logo = null;

    #[Validate('nullable|image|max:1024')]
    public $favicon = null;

    public ?string $oldLogo = null;
    public ?string $oldFavicon = null;

    public function setInstitution(Institution $institution): void
    {
        $this->institution = $institution;
        $this->name = $institution->name ?? '';
        $this->vision = $institution->vision ?? '';
        $this->mission = $institution->mission ?? '';
        $this->motto = $institution->motto ?? '';
        $this->tagline = $institution->tagline ?? '';
        $this->description = $institution->description ?? '';
        $this->history = $institution->history ?? '';
        $this->address = $institution->address ?? '';
        $this->email = $institution->email ?? '';
        $this->whatsapp = $institution->whatsapp ?? '';
        $this->whatsapp_2 = $institution->whatsapp_2 ?? '';
        $this->facebook = $institution->facebook ?? '';
        $this->instagram = $institution->instagram ?? '';
        $this->youtube = $institution->youtube ?? '';
        $this->twitter = $institution->twitter ?? '';
        $this->tiktok = $institution->tiktok ?? '';
        $this->website = $institution->website ?? '';
        $this->meta_title = $institution->meta_title ?? '';
        $this->meta_description = $institution->meta_description ?? '';
        $this->meta_keywords = $institution->meta_keywords ?? '';
        $this->timezone = $institution->timezone ?? 'Asia/Jakarta';
        $this->locale = $institution->locale ?? 'id';
        $this->established_year = $institution->established_year ?? null;
        $this->accreditation = $institution->accreditation ?? '';
        $this->oldLogo = $institution->logo ?? null;
        $this->oldFavicon = $institution->favicon ?? null;
    }

    public function save(): void
    {
        // Validasi manual untuk established_year
        $rules = [
            'name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'whatsapp_2' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:255',
            'locale' => 'nullable|string|max:10',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'accreditation' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'motto' => $this->motto,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'history' => $this->history,
            'address' => $this->address,
            'email' => $this->email,
            'whatsapp' => $this->whatsapp,
            'whatsapp_2' => $this->whatsapp_2,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'twitter' => $this->twitter,
            'tiktok' => $this->tiktok,
            'website' => $this->website,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'established_year' => $this->established_year,
            'accreditation' => $this->accreditation,
        ];

        if ($this->logo) {
            if ($this->oldLogo) {
                Storage::disk('public')->delete($this->oldLogo);
            }
            $data['logo'] = $this->logo->store('institutions', 'public');
        }

        if ($this->favicon) {
            if ($this->oldFavicon) {
                Storage::disk('public')->delete($this->oldFavicon);
            }
            $data['favicon'] = $this->favicon->store('institutions', 'public');
        }

        if ($this->institution && $this->institution->exists) {
            $this->institution->update($data);
        } else {
            $this->institution = Institution::create($data);
        }

        $this->reset(['logo', 'favicon']);
        $this->oldLogo = $this->institution->logo ?? null;
        $this->oldFavicon = $this->institution->favicon ?? null;
    }

    public function removeLogo(): void
    {
        if ($this->oldLogo) {
            Storage::disk('public')->delete($this->oldLogo);
            $this->institution->update(['logo' => null]);
            $this->oldLogo = null;
        }
    }

    public function removeFavicon(): void
    {
        if ($this->oldFavicon) {
            Storage::disk('public')->delete($this->oldFavicon);
            $this->institution->update(['favicon' => null]);
            $this->oldFavicon = null;
        }
    }

    public function getLogoUrl(): string
    {
        return $this->oldLogo
            ? asset('storage/' . $this->oldLogo)
            : asset('images/logo.png');
    }

    public function getFaviconUrl(): string
    {
        return $this->oldFavicon
            ? asset('storage/' . $this->oldFavicon)
            : asset('images/favicon.ico');
    }
}
