@extends('layouts.quiz')
@section('content')


    <div class="row  h-100 mt-5">
        <div class="col-lg-12">
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="alert alert-info h-100">
                        <div class="row h-100">
                            <div class="col">
                                <strong id="sw-time" class="">00:00</strong>
                                <strong id="f-time"></strong>
                            </div>
                            <div class="col rtl">
                                <strong id="question_num">سوال : <span id="qNum">0</span>/{{$qNum}}</strong>
                                <input type="hidden" id="Qnum" value="50">
                            </div>
                            <div class="col-md-6  rtl" id="msg">
                                {{__('در چالش با ')}}<strong>{{$name2}}</strong>
                            </div>
                            <div class="col-md-6  rtl d-none" id="fmsg">
                                {{__('پیام درخواست جواب به  ')}}<strong>{{$name2}}</strong>{{__(' ارسال شده. نتیجه را پس از پاسخ ایشان در پیشخوان دنبال کنید.  ')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row h-75 ">
                <div class="col-lg-12 ">
                    <div class="card h-85 ">
                        <div class="card-header h-25 text-center">
                                <div class="row align-items-center h-100 ">
                                    <h5 class=" w-100" id="question">{{ __('جواب سوالات قابل اصلاح نمیباشد') }}</h5>
                                </div>
                        </div>
                        <div class="card-body h-100">
                            <div class="row  h-100">
                                <div class="col-md-6">
                                    <button class="btn btn-primary w-100 h-85 " id="start"><h1 >{{__('شروع')}}</h1></button>
                                    <a href="{{route('home')}}" class="btn btn-primary w-100 d-none" id="finish"><h3>{{__('برگشت به پیشخوان')}}</h3></a>
                                    <button class="btn btn-primary w-100 h-85 d-none" id="mybtn"><h1>{{__('خودم')}}</h1></button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary w-100 h-85 d-none" id="hisbtn"><h1>{{$name2}}</h1></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>








    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript">

        $('#start').click(function(event){
            $('#start').addClass('d-none');
            $('#hisbtn').removeClass('d-none');
            $('#mybtn').removeClass('d-none');
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.run')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    start:true,
                },
                success:function(response){
                        if(response.question){
                            //console.log(response);
                            $thisQs=response.question;
                            $('#Qnum').val(response.qNum);
                            $thisQs='کدام یک '+''+$thisQs+''+' هستید؟';
                            $('#question').text($thisQs);
                            $qNum=parseInt($('#qNum').text());
                            $('#qNum').text($qNum+1);

                        }
                        if(response.error){
                            $('#question').text(response.error);
                            $('#hisbtn').addClass('d-none');
                            $('#mybtn').addClass('d-none');
                            $('#sw-time').addClass('d-none');
                            $('#question_num').addClass('d-none');
                            $('#msg').addClass('d-none');
                            $('#fmsg').removeClass('d-none');
                            $('#finish').removeClass('d-none');

                        }
                },
                error: function(response){alert('ارتباط با سرور مقدور نمیباشد');}
            });
        });


        $('#hisbtn').click(function(event){
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.run')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    aNs:0,
                    qNum:$('#Qnum').val(),
                    Answer:true,
                },
                success:function(response){
                    if(response.question){
                        $thisQs=response.question;
                        if($thisQs!='Done') {
                            $('#Qnum').val(response.qNum);
                            $thisQs = 'کدام یک ' + '' + $thisQs + '' + ' هستید؟';
                            $('#question').text($thisQs);
                            $qNum = parseInt($('#qNum').text());
                            $('#qNum').text($qNum + 1);
                        }else{
                            $timed=$('#sw-time').text();
                            $('#question').text('با تشکر از شرکت در چالش صداقت');
                            $('#hisbtn').addClass('d-none');
                            $('#mybtn').addClass('d-none');
                            $('#sw-time').addClass('d-none');
                            $('#f-time').text($timed);
                            $('#question_num').addClass('d-none');
                            $('#msg').addClass('d-none');
                            $('#fmsg').removeClass('d-none');
                            $('#finish').removeClass('d-none');
                        }

                    }else
                        if(response.error)
                        alert(response.error);// alert('خطا در دریافت اطلاعات از سرور');
                },
                error: function(response) {
                    alert('ارتباط با سرور مقدور نمیباشد');
                }
            });
        });







        $('#mybtn').click(function(event){
            event.preventDefault();
            $.ajax({
                url: "{{route('challenge.run')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    aNs:1,
                    qNum:$('#Qnum').val(),
                    Answer:true,
                },
                success:function(response){
                    if(response.question){
                        $thisQs=response.question;
                        if($thisQs!='Done') {
                            $('#Qnum').val(response.qNum);
                            $thisQs = 'کدام یک ' + '' + $thisQs + '' + ' هستید؟';
                            $('#question').text($thisQs);
                            $qNum = parseInt($('#qNum').text());
                            $('#qNum').text($qNum + 1);
                        }else{
                            $timed=$('#sw-time').text();
                            $('#question').text('با تشکر از شرکت در چالش صداقت');
                            $('#hisbtn').addClass('d-none');
                            $('#mybtn').addClass('d-none');
                            $('#sw-time').addClass('d-none');
                            $('#f-time').text($timed);
                            $('#question_num').addClass('d-none');
                            $('#msg').addClass('d-none');
                            $('#fmsg').removeClass('d-none');
                            $('#finish').removeClass('d-none');
                        }

                    }else  console.log(response);// alert('خطا در دریافت اطلاعات از سرور');
                },
                error: function(response) {
                    alert('ارتباط با سرور مقدور نمیباشد');
                }
            });
        });






        var sw = {
            /* [INIT] */
            etime : null, // holds HTML time display
            erst : null, // holds HTML reset button
            ego : null, // holds HTML start/stop button
            timer : null, // timer object
            now : 0, // current timer
            init : function () {
                // Get HTML elements
                sw.etime = document.getElementById("sw-time");
                //sw.erst = document.getElementById("sw-rst");
                sw.ego = document.getElementById("start");

                // Attach listeners
                //sw.erst.addEventListener("click", sw.reset);
                //sw.erst.disabled = false;
                sw.ego.addEventListener("click", sw.start);
                sw.ego.disabled = false;
            },

            /* [ACTIONS] */
            tick : function () {
                // tick() : update display if stopwatch running

                // Calculate hours, mins, seconds
                sw.now++;
                var remain = sw.now;
                var hours = Math.floor(remain / 3600);
                remain -= hours * 3600;
                var mins = Math.floor(remain / 60);
                remain -= mins * 60;
                var secs = remain;

                // Update the display timer
                if (hours<10) { hours = "0" + hours; }
                if (mins<10) { mins = "0" + mins; }
                if (secs<10) { secs = "0" + secs; }
                sw.etime.innerHTML =mins + ":" + secs;//hours + ":" + mins + ":" + secs;
            },

            start : function () {
                // start() : start the stopwatch

                sw.timer = setInterval(sw.tick, 1000);
                sw.ego.value = "Stop";
                sw.ego.removeEventListener("click", sw.start);
                //sw.ego.addEventListener("click", sw.stop);
            },

            stop  : function () {
                // stop() : stop the stopwatch

                clearInterval(sw.timer);
                sw.timer = null;
                sw.ego.value = "Start";
                sw.ego.removeEventListener("click", sw.stop);
                sw.ego.addEventListener("click", sw.start);
            },

            reset : function () {
                // reset() : reset the stopwatch

                // Stop if running
                if (sw.timer != null) { sw.stop(); }

                // Reset time
                sw.now = -1;
                sw.tick();
            }
        };

        window.addEventListener("load", sw.init);


    </script>
@endsection

