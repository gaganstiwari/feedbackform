<div>
<!-- @if(isset($policy))
    <input type="hidden" name="token_number" value="{{ $policy }}">
@endif -->
    {{-- Thank You Message (Full Screen) --}}
    @if ($formSubmitted)
        <div class="thank-you-overlay" id="thankYouMessage" x-data="{ countdown: 4 }" x-init="
            let timer = setInterval(() => {
                countdown--;
                document.getElementById('countdown').textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = 'https://callmedilife.com/';
                }
            }, 1000);
        ">
            <div class="thank-you-content">
                <div class="thank-you-icon">✓</div>
                <h2 class="thank-you-title">Thank You!</h2>
                <p class="thank-you-message">We appreciate your feedback. Redirecting you to our website...</p>
                <div class="thank-you-countdown">
                    <span id="countdown">4</span> seconds
                </div>
            </div>
        </div>
        
        <script>
            // Fallback countdown if Alpine.js is not available
            (function() {
                let seconds = 4;
                const countdownElement = document.getElementById('countdown');
                if (!countdownElement) return;
                
                const countdownInterval = setInterval(() => {
                    seconds--;
                    if (countdownElement) {
                        countdownElement.textContent = seconds;
                    }
                    
                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        window.location.href = 'https://callmedilife.com/';
                    }
                }, 1000);
            })();
        </script>
    @endif

    {{-- Success/Error Messages --}}
    @if (session()->has('success') && !$formSubmitted)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('rating_saved') && !$formSubmitted)
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460;">
            <strong>✓</strong> {{ session('rating_saved') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($question && !$formSubmitted)
        <div class="form-group col-sm-12 flex-column d-flex mt-4 dynamic-wrapper">

            <label class="form-control-label customlabel dynamic-question">
                {{ $question }}
            </label>

            {{-- MAIN CHECKBOX OPTIONS --}}
            <div class="checkbox-container mt-3">
                @foreach ($checkboxOptions as $option)
                    <div class="form-check" wire:key="main-option-{{ $option }}">
                        <input 
                            type="checkbox" 
                            class="form-check-input"
                            id="main-{{ $loop->index }}"
                            wire:model.live="selectedOptions"
                            value="{{ $option }}"
                        >
                        <label class="form-check-label" for="main-{{ $loop->index }}">
                            {{ $option }}
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- SUB OPTIONS (AUTO GENERATED) --}}
            @if (!empty($subOptions))
                <div class="mt-4 p-3 border rounded bg-light">
                    <label class="form-control-label customlabel">
                        Please specify the issue(s):
                    </label>

                    @foreach ($subOptions as $main => $items)
                        <div class="mt-3" wire:key="sub-group-{{ $main }}">
                            <strong>{{ $main }}</strong>

                            @foreach ($items as $sub)
                                <div class="form-check ms-3 mt-1" wire:key="sub-option-{{ md5($main . $sub) }}">
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input"
                                        id="sub-{{ md5($main . $sub) }}"
                                        wire:model="selectedSubOptions"
                                        value="{{ $sub }}"
                                    >
                                    <label class="form-check-label" for="sub-{{ md5($main . $sub) }}">
                                        {{ $sub }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- COMMENT BOX --}}
            <div class="form-group col-sm-12 flex-column d-flex mt-4">
                <label class="form-control-label customlabel">
                    Kindly enter your additional comments regarding your medical experience:
                </label>

                <textarea 
                    name="medical_experience_comment" 
                    placeholder="Type here... Tell us your comments"
                    class="form-control comment-box"
                    rows="3"
                    wire:model="comment"
                ></textarea>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="text-center mt-4">
                <button type="button" wire:click="submit" class="form-btn btn btn-primary">Submit</button>
            </div>
        </div>
    @endif
</div>
