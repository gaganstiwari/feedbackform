<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feedback;
use Illuminate\Support\Facades\Log;

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
    public $token_number = null;

    public $feedback_id = null;
    public $formSubmitted = false;

    protected $listeners = [
        'npsSelected' => 'handleNpsClick',
        'policyReceived' => 'setPolicy',
    ];

    public function mount($requestid = null, $token_number = null)
    {
        $this->requestid = $requestid;
        $this->token_number = $token_number;

        // If we have token_number, use it as requestid
        if ($this->token_number && !$this->requestid) {
            $this->requestid = $this->token_number;
        }

        // Load existing draft if available
        if ($this->requestid) {
            $draft = Feedback::where(function($query) {
                if ($this->token_number) {
                    $query->where('token_number', $this->token_number);
                }
                if ($this->requestid) {
                    $query->orWhere('requestid', $this->requestid);
                }
            })
            ->where(function($query) {
                $query->whereNull('remark')
                      ->orWhere('remark', '!=', 'completed');
            })
            ->first();

            if ($draft) {
                $this->feedback_id = $draft->id;
                $this->nps_score = $draft->nps_score;
                $this->selectedOptions = $draft->main_options ?? [];
                $this->selectedSubOptions = $draft->sub_options ?? [];
                $this->comment = $draft->comment ?? '';
                
                // Trigger question display if score exists
                if ($this->nps_score !== null) {
                    $this->handleNpsClick($this->nps_score);
                }
            }
        }
    }

    public function setPolicy($data)
    {
        $this->requestid = $data['requestid'] ?? null;
    }

    private function autoSave()
    {
        if (!$this->requestid && !$this->token_number) return;

        $data = [
            'token_number' => $this->token_number,
            'requestid' => $this->requestid,
            'nps_score' => $this->nps_score,
            'main_options' => $this->selectedOptions ?? [],
            'sub_options' => $this->selectedSubOptions ?? [],
            'comment' => $this->comment,
            'status' => 'draft',
            'remark' => null,
        ];

        if (!$this->feedback_id) {
            $feedback = Feedback::create($data);
            $this->feedback_id = $feedback->id;
            Log::info('Feedback draft created', ['id' => $feedback->id]);
        } else {
            Feedback::where('id', $this->feedback_id)->update($data);
            Log::info('Feedback draft updated', ['id' => $this->feedback_id]);
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
        // Validate NPS score is selected
        if ($this->nps_score === null) {
            session()->flash('error', 'Please select an NPS score first.');
            return;
        }

        // Validate NPS score range
        if ($this->nps_score < 0 || $this->nps_score > 10) {
            session()->flash('error', 'Invalid NPS score.');
            return;
        }

        try {
            $data = [
                'token_number' => $this->token_number,
                'requestid' => $this->requestid,
                'nps_score' => (int) $this->nps_score,
                'main_options' => !empty($this->selectedOptions) ? $this->selectedOptions : null,
                'sub_options' => !empty($this->selectedSubOptions) ? $this->selectedSubOptions : null,
                'comment' => !empty($this->comment) ? trim($this->comment) : null,
                'remark' => 'completed',
                'status' => 'completed',
            ];

            if ($this->feedback_id) {
                // Update existing feedback
                Feedback::where('id', $this->feedback_id)->update($data);
                Log::info('Feedback completed', ['id' => $this->feedback_id]);
            } else {
                // Create new feedback
                $feedback = Feedback::create($data);
                $this->feedback_id = $feedback->id;
                Log::info('Feedback created and completed', ['id' => $feedback->id]);
            }

            $this->formSubmitted = true;
            session()->flash('success', 'Thank you for your feedback!');

        } catch (\Exception $e) {
            Log::error('Feedback submission error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while saving your feedback. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.feedback-form');
    }
}
