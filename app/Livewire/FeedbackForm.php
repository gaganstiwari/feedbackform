<?php

namespace App\Livewire;

use App\Models\Feedback;
use Livewire\Component;

class FeedbackForm extends Component
{
    public $medical_experience = '';
    public $question = '';
    public $checkboxOptions = [];
    public $selectedOptions = [];
    public $nps_score = null;
    public $comment = '';

    public $formSubmitted = false;          // ✅ Fix for undefined variable

    public $subOptions = [];
    public $selectedSubOptions = [];

    public $policy = null;                  // ✅ NEW: store policy

    protected $listeners = [
        'npsSelected' => 'handleNpsClick',
        'policyReceived' => 'setPolicy',    // ✅ NEW: listener for policy
    ];

    // ✅ Receive policy from Blade (via JS)
    public function setPolicy($data)
    {
        $this->policy = $data['policy'];
    }

    public function handleNpsClick($score)
    {
        $this->nps_score = $score;

        if ($score <= 6) {
            $this->medical_experience = 'Poor';
        } elseif ($score <= 8) {
            $this->medical_experience = 'Good';
        } else {
            $this->medical_experience = 'Excellent';
        }

        $this->question = match ($this->medical_experience) {
            'Excellent' => 'What went perfect for you?',
            'Good' => 'What could have been better?',
            default => 'We are sorry your experience was not ideal. Please tell us what went wrong.',
        };

        // These are your ORIGINAL options (unchanged)
        $this->checkboxOptions = [
            "Staff Professionalism",
            "Staff Coordination",
            "Facilities and Services",
            "Hygiene",
            "Call Center Services"
        ];
    }

    public function updatedSelectedOptions()
    {
        // ORIGINAL unchanged mapping
        $map = [
            'Staff Professionalism' => [
                'Rude / Inappropriate behaviour',
                'Incorrect / incomplete information',
                'Phlebotomy skill issue',
                'Phlebotomy protocol not followed'
            ],
            'Staff Coordination' => [
                'Phlebo reached late / not reached',
                'Phlebo attendance / allocation not managed properly',
                'Slot not allocated',
                'Home visit not performed as per schedule'
            ],
            'Facilities and Services' => [
                'High waiting time',
                'Infrastructure related issue',
                'Health check-up denied',
                'Non-serviceable radiology area'
            ],
            'Hygiene' => [
                'Lack of hygiene / cleanliness'
            ],
            'Call Center Services' => [
                'Incorrect information provided',
                'Incomplete guidance'
            ],
        ];

        $this->subOptions = [];

        foreach ($this->selectedOptions as $option) {
            $this->subOptions[$option] = $map[$option] ?? [];
        }
    }

    public function submit()
    {
        if (!$this->nps_score) {
            session()->flash('error', 'Please select an NPS score first.');
            return;
        }

        // ✅ Save policy number
        Feedback::create([
            'token_number' => $this->policy,     // <-- NEW: save policy
            'nps_score' => $this->nps_score,
            'main_options' => $this->selectedOptions,
            'sub_options' => $this->selectedSubOptions,
            'comment' => $this->comment,
        ]);

        session()->flash('success', 'Thank you for your feedback!');

        // ORIGINAL behavior
        $this->reset();
        $this->formSubmitted = true;         // <-- no more undefined variable
    }

    public function render()
    {
        return view('livewire.feedback-form');
    }
}
