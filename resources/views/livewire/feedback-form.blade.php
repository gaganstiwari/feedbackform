<div>

    {{-- ================================
        ✅ THANK YOU PAGE
    ================================= --}}
    @if ($formSubmitted)
        <div class="thank-you-overlay" id="thankYouMessage">
            <div class="thank-you-content">
                <div class="thank-you-icon">✓</div>
                <h2>Thank You!</h2>
                <p>Redirecting you shortly...</p>

                <div class="thank-you-countdown">
                    <span id="countdown">4</span> seconds
                </div>
            </div>
        </div>

        <script>
            let sec = 4;
            const cd = document.getElementById('countdown');
            setInterval(() => {
                sec--;
                cd.textContent = sec;
                if (sec <= 0) {
                    window.location.href = "https://callmedilife.com/";
                }
            }, 1000);
        </script>
    @endif


    {{-- ================================
        ✅ MAIN FORM
    ================================= --}}
    @if ($question && !$formSubmitted)
        <div class="form-group col-sm-12 mt-4">

            {{-- ⭐ MAIN QUESTION TITLE --}}
            <label class="form-control-label customlabel">
                {{ $question }}
            </label>


            {{-- ================================
                ⭐ MAIN OPTIONS
            ================================= --}}
            <div class="checkbox-container mt-3">
                @foreach ($checkboxOptions as $option)
                    <div class="form-check" 
                         wire:key="main-{{ md5($option) }}">

                        <input 
                            type="checkbox"
                            class="form-check-input"
                            wire:model.live="selectedOptions"
                            value="{{ $option }}"
                            id="main-{{ md5($option) }}"
                        >

                        <label for="main-{{ md5($option) }}" class="form-check-label">
                            {{ $option }}
                        </label>

                    </div>
                @endforeach
            </div>


            {{-- ================================
                ⭐ SUB OPTIONS
            ================================= --}}
            @if (!empty($subOptions))
                <div class="p-3 border rounded bg-light mt-4">
                    <label class="form-control-label customlabel">Please specify:</label>

                    @foreach ($subOptions as $main => $items)

                        @if (in_array($main, $selectedOptions))

                            <div class="mt-3" 
                                 wire:key="sub-group-{{ md5($main) }}">

                                <strong>{{ $main }}</strong>

                                @foreach ($items as $item)
                                    <div class="form-check ms-3 mt-1" 
                                         wire:key="sub-{{ md5($main.$item) }}">

                                        <input 
                                            type="checkbox"
                                            class="form-check-input"
                                            wire:model.live="selectedSubOptions"
                                            value="{{ $item }}"
                                            id="sub-{{ md5($main.$item) }}"
                                        >

                                        <label class="form-check-label" 
                                               for="sub-{{ md5($main.$item) }}">
                                            {{ $item }}
                                        </label>

                                    </div>
                                @endforeach

                            </div>

                        @endif

                    @endforeach

                </div>
            @endif



            {{-- ================================
                ⭐ COMMENT BOX
            ================================= --}}
            <div class="form-group col-sm-12 mt-4">
                <label class="form-control-label customlabel">Additional comments:</label>

                <textarea class="form-control"
                          rows="3"
                          placeholder="Write your comments here..."
                          wire:model.live="comment"></textarea>
            </div>


            {{-- ================================
                ⭐ SUBMIT BUTTON
            ================================= --}}
            <div class="text-center mt-4">
                <button class="btn btn-primary" 
                        wire:click="submit">
                        Submit
                </button>
            </div>

        </div>
    @endif

</div>
