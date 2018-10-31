@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h4 class="font-weight-bold">문제</h4>
                <div class="card mb-3" style="min-height: 150px;">
                    <div class="card-body">
                        <p id="question"></p>
                    </div>
                </div>
                <h4 class="font-weight-bold">수식/문제 입력기</h4>
                <div class="card mb-5">
                    <div class="card-body">
                        <form action="/question" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="question-input">문제</label>
                                <textarea id="question-input" name="question" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="answer">정답</label>
                                <input type="text" name="answer" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-3">문제 형태</label>
                                <div class="col-9">
                                    <input id="단답형" type="radio" name="type" value="단답형" checked> <label
                                            for="단답형">단답형</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input id="선택형" type="radio" name="type" value="선택형"> <label for="선택형">선택형</label>
                                </div>
                            </div>
                            <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="출제하기">
                            </div>
                        </form>
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
    <script>
        var isTyping = false;
        $('#question-input').on('keyup', function () {
            // isTyping = true;
            $('#question').html($(this).val());
        });
        setInterval(function () {
            if (!isTyping) {
                MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            }
        }, 1000);
        $('#question-input')
    </script>
@endsection