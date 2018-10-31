@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <h4 class="font-weight-bold">문제</h4>
                <div class="card">
                    <div class="card-body">
                        <p id="question">{{ $question->question }}</p>

                        <form id="answer_form">
                            <input type="hidden" id="answer_value" value="{{ $question->answer }}">
                            @if($question->type == "단답형")
                                <div class="input-group">
                                    <input type="number" id="answer" name="answer" class="form-control"
                                           autocomplete="off" maxlength="3" pattern="[0-9]*">
                                    <div class="input-group-append">
                                        <button id="checkAnswer" class="btn btn-outline-secondary" type="submit">
                                            정답확인
                                        </button>
                                    </div>
                                </div>
                                <div id="feedback" class="d-block"></div>
                            @else
                                @php
                                    $answer_position = rand(1, 5);
                                @endphp
                                @for($i=1; $i<=5; $i++)
                                    &nbsp;
                                    @if($i == $answer_position)
                                        <input id="answer_option{{ $i }}" class="isAnswer" name="answer" type="radio">
                                        <label class="answerLabel" for="answer_option{{ $i }}"
                                               data-index="{{ $i }}"></label>
                                    @else
                                        <input id="answer_option{{ $i }}" name="answer" type="radio"> <label
                                                for="answer_option{{ $i }}" data-index="{{ $i }}"></label>
                                    @endif
                                    &nbsp;
                                @endfor
                                <input type="submit" id="checkAnswer" class="btn btn-outline-secondary" value="정답 확인">
                                <div id="feedback" class="d-block"></div>
                            @endif
                        </form>
                    </div>
                </div>
                @isset($question->modified)
                    <a href="/question/{{ $question->id }}/modified">변형 문제 풀어보기</a>
                @endisset
                <h4 class="font-weight-bold mt-3">연습장</h4>
                <div class="card mb-5">
                    <div class="card-body p-0" style="min-height: 400px; overflow: hidden;">
                        <div id="toolbox">
                            <i class="tool active fas fa-paint-brush" data-mode="brush"></i>
                            <i class="tool fas fa-eraser" data-mode="eraser"></i>
                            <i id="clear" class="far fa-file"></i>
                        </div>
                        <div id="canvas"></div>
                    </div>
                    <div class="card-body">
                        <textarea rows="5" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML'
            async></script>
    <script src="https://unpkg.com/mathjs@5.2.2/dist/math.min.js"></script>
    <script src="https://unpkg.com/konva@2.4.2/konva.min.js"></script>
    <script>
        @if(Request::segment(3) === "modified")
        if (`{{ isset($question->modified) }}`) {
            question = String.raw`{!! $question->modified !!}`;
            scope_text = '{!! $question->scope !!}';
            scope = JSON.parse(scope_text);

            textReplace(question, scope, 1, 20);

            {!! $question->algorithm !!}
        }
        @endif


        // answer = (scope['c1'] * scope['b1'] - scope['c2'] * scope['a1']) + "/" + (scope['c1'] - scope['c2']);
        // $('#answer_value').val(answer);

        if (`{{ $question->type == "선택형" }}`) {
            var change = getRandomInt(3) + 1;
            var answerPosition = $('.answerLabel').data('index');
            console.log('ans' + answerPosition);

            answer = $('#answer_value').val();
            if (answer.includes('/')) {
                var fraction = answer.split('/');
                $('.answerLabel').text('\\(\\frac{' + fraction[0] + '}{' + fraction[1] + '}\\)');

                for (i = 1; i <= 5; i++) {
                    // answer = math.parse(answer);
                    if (i != answerPosition) {
                        new_label = math.fraction(math.eval(`${answer} + ((${i} - ${answerPosition}) * ${change})`));
                    } else {
                        new_label = math.fraction(math.eval(answer));
                    }
                    console.log(new_label);
                    // $('label[data-index="'+i+'"]').text('\\( '+new_label+' \\)');
                    if (new_label.s < 0) {
                        new_label.s = "-";
                    } else {
                        new_label.s = "";
                    }
                    $('label[data-index="' + i + '"]').text(`\\( ${new_label.s}\\frac{${new_label.n}}{${new_label.d}} \\)`);
                }
            } else {

                for (i = 1; i <= 5; i++) {
                    if (i != answerPosition) {
                        new_label = math.eval(`${answer} + ((${i} - ${answerPosition}) * ${change})`);
                    } else {
                        new_label = math.eval(answer);
                    }
                    console.log(new_label);
                    $('label[data-index="' + i + '"]').text('\\( ' + new_label + ' \\)');
                }
                // $('.answerLabel').text('\\('+answer+'\\)');
            }
        }

        $('#answer_form').on('submit', function (e) {
            e.preventDefault();
            var feedback = $('#feedback');
            if (`{{ $question->type == "선택형" }}`) {
                $('#checkAnswer').removeClass('btn-outline-secondary').removeClass('btn-outline-success').removeClass('btn-outline-danger');
                feedback.removeClass('invalid-feedback').removeClass('valid-feedback');
                if ($('.isAnswer').prop('checked')) {
                    feedback.text('정답입니다!').addClass('valid-feedback');
                    $('#checkAnswer').addClass('btn-outline-success');
                } else {
                    feedback.text('틀렸습니다!').addClass('invalid-feedback');
                    $('#checkAnswer').addClass('btn-outline-danger');
                }
            } else {
                var input = $('#answer');
                input.removeClass('is-invalid').removeClass('is-valid');
                feedback.removeClass('invalid-feedback').removeClass('valid-feedback');
                if (input.val() != $('#answer_value').val()) {
                    input.addClass('is-invalid');
                    feedback.text('틀렸습니다!').addClass('invalid-feedback');
                } else {
                    input.addClass('is-valid');
                    feedback.text('정답입니다!').addClass('valid-feedback');
                }
            }
        });

        function getRandomInt(max) {
            return Math.floor(Math.random() * Math.floor(max));
        }

        function textReplace(question, scope, min=0, max=10) {
            for (var index in scope) {
                scope[index] = scope[index] + getRandomInt(max);
                question = question.replace(new RegExp(index, 'g'), scope[index]);
            }
            $('#question').text(question);
        }
    </script>
    <script>
        var width = window.innerWidth;
        var height = 400;

        // first we need Konva core things: stage and layer
        var stage = new Konva.Stage({
            container: 'canvas',
            width: width,
            height: height
        });

        var layer = new Konva.Layer();
        stage.add(layer);

        // then we are going to draw into special canvas element
        var canvas = document.createElement('canvas');
        canvas.width = stage.width();
        canvas.height = stage.height();

        // created canvas we can add to layer as "Konva.Image" element
        var image = new Konva.Image({
            image: canvas,
            x: 0,
            y: 0,
        });
        layer.add(image);
        stage.draw();

        // Good. Now we need to get access to context element
        var context = canvas.getContext('2d');
        context.strokeStyle = "#000";
        context.lineJoin = "round";
        context.lineWidth = 3;

        var isPaint = false;
        var lastPointerPosition;
        var mode = 'brush';

        // now we need to bind some events
        // we need to start drawing on mousedown
        // and stop drawing on mouseup
        image.on('mousedown touchstart', function () {
            isPaint = true;
            lastPointerPosition = stage.getPointerPosition();

        });

        // will it be better to listen move/end events on the window?

        stage.addEventListener('mouseup touchend', function () {
            isPaint = false;
        });

        // and core function - drawing
        stage.addEventListener('mousemove touchmove', function () {
            if (!isPaint) {
                return;
            }

            if (mode === 'brush') {
                context.lineWidth = 2;
                context.globalCompositeOperation = 'source-over';
            }
            if (mode === 'eraser') {
                context.lineWidth = 20;
                context.globalCompositeOperation = 'destination-out';
            }
            context.beginPath();

            var localPos = {
                x: lastPointerPosition.x - image.x(),
                y: lastPointerPosition.y - image.y()
            };
            context.moveTo(localPos.x, localPos.y);
            var pos = stage.getPointerPosition();
            localPos = {
                x: pos.x - image.x(),
                y: pos.y - image.y()
            };
            context.lineTo(localPos.x, localPos.y);
            context.closePath();
            context.stroke();

            lastPointerPosition = pos;
            layer.batchDraw();
        });

        $('.tool').on('click', function () {
            $('.tool').removeClass('active');
            $(this).addClass('active');
            mode = $(this).data('mode');
        });

        $('#clear').on('click', function () {
            context.clearRect(0, 0, 1440, 400);
            layer.clear();
            stage.draw();
        })
    </script>
@endsection