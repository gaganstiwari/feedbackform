<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feedback;

class FeedbackForm extends Component
{
    public $medical_experience = '';
    public $question = '';
    public $checkboxOptions = [];
    public $selectedOptions = [];
    public $subOptions = [];
    public $selectedSubOptions = [];

    public $comment = '';
    public $nps_score = null;
    public $requestid = null;

    public $feedback_id = null;
    public $formSubmitted = false;

    protected $listeners = [
        'npsSelected' => 'handleNpsClick',
        'policyReceived' => 'setPolicy',
    ];

    public function mount($requestid = null)
    {
        $this->requestid = $requestid;

        if ($this->requestid) {
            $draft = Feedback::where('requestid', $this->requestid)
                ->whereNull('remark')
                ->first();

            if ($draft) {
                $this->feedback_id = $draft->id;
                $this->nps_score = $draft->nps_score;
                $this->selectedOptions = $draft->main_options ?? [];
                $this->selectedSubOptions = $draft->sub_options ?? [];
                $this->comment = $draft->comment ?? '';
            }
        }
    }

    public function setPolicy($data)
    {
        $this->requestid = $data['requestid'] ?? null;
    }

    private function autoSave()
    {
        if (!$this->requestid) return;

        if (!$this->feedback_id) {
            $feedback = Feedback::create([
                'requestid' => $this->requestid,
                'nps_score' => $this->nps_score,
                'main_options' => $this->selectedOptions ?? [],
                'sub_options' => $this->selectedSubOptions ?? [],
                'comment' => $this->comment,
                'status' => null,
                'remark' => null,
            ]);
            $this->feedback_id = $feedback->id;
        } else {
            Feedback::where('id', $this->feedback_id)->update([
                'nps_score' => $this->nps_score,
                'main_options' => $this->selectedOptions ?? [],
                'sub_options' => $this->selectedSubOptions ?? [],
                'comment' => $this->comment,
            ]);
        }
    }

    public function handleNpsClick($score)
    {
        $this->nps_score = $score;

        $this->medical_experience = match (true) {
            $score >= 9 => 'Excellent',
            $score >= 7 => 'Good',
            default => 'Poor',
        };

        $this->question = match ($this->medical_experience) {
            'Excellent' => 'What went perfect for you?',
            'Good' => 'What could have been better?',
            default => 'We are sorry your experience was not ideal. Please tell us what went wrong.',
        };

        $this->checkboxOptions = [
            "Staff Professionalism",
            "Staff Coordination",
            "Facilities and Services",
            "Hygiene",
            "Call Center Services"
        ];

        $this->selectedOptions = [];
        $this->selectedSubOptions = [];
        $this->subOptions = [];

        $this->autoSave();
    }

    public function updatedSelectedOptions()
    {
        if ($this->nps_score >= 9) {
            $this->subOptions = [];
            $this->selectedSubOptions = [];
        } else {
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
                'Hygiene' => ['Lack of hygiene / cleanliness'],
                'Call Center Services' => ['Incorrect information provided', 'Incomplete guidance'],
            ];

            $this->subOptions = [];
            foreach ($this->selectedOptions as $opt) {
                if (isset($map[$opt])) $this->subOptions[$opt] = $map[$opt];
            }
        }

        $this->autoSave();
    }

    public function updatedSelectedSubOptions() { $this->autoSave(); }
    public function updatedComment() { $this->autoSave(); }

    public function saveForm()
    {
        $this->autoSave();

        Feedback::where('id', $this->feedback_id)->update([
            'remark' => 'submitted',
            'status' => 'completed',
        ]);

        $this->formSubmitted = true;
    }

    public function submit()
    {
        if (!$this->feedback_id) {
            session()->flash('error', 'Please fill feedback.');
            return;
        }

        Feedback::where('id', $this->feedback_id)->update([
            'remark' => 'completed',
            'status' => 'completed',
        ]);

        $this->formSubmitted = true;

        $this->dispatchBrowserEvent('feedbackSubmitted');
    }

    public function render()
    {
        return view('livewire.feedback-form');
    }
}
