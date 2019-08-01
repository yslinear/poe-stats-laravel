<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Ladder</title>
    @include('includes.head')
    <script src="https://kit.fontawesome.com/f2d111bece.js"></script>
    <!-- Styles -->
    <style>
    </style>
</head>

<body>
    @include('includes.navbar')
    <div class="container-fluid">
        <div class="row my-1">
            <div class="col">
                <div class="btn-group" style="cursor: pointer">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" id="choose_league" value="ggg_tmpsc">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" onclick="refreshData('ggg_tmpsc')">Legion</a>
                        <a class="dropdown-item" onclick="refreshData('ggg_tmphc')">HC Legion</a>
                        <a class="dropdown-item" onclick="refreshData('ggg_tmpssfsc')">SSF Legion</a>
                        <a class="dropdown-item" onclick="refreshData('ggg_tmpssfhc')">SSF HC Legion</a>
                    </div>
                </div>
                <div class="btn-group" style="cursor: pointer" id="pagination">
                </div>
            </div>
        </div>
        <div class="row my-1">
            <div class="col">
                <input class="form-control form-control-sm" type="text" placeholder="Search(more than 3 characters)"
                    aria-label="Search" id="searchBox">
            </div>
        </div>
        <div class="row">
            <div class="col table-responsive-sm">
                <table class="table table-hover table-light table-striped table-sm " style="min-width: 100%"
                    id="datatable">
                </table>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    /* When the user scrolls down, hide the navbar. When the user scrolls up, show the navbar */
    var prevScrollpos = window.pageYOffset;
    window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
                $('.navbar-collapse').collapse('hide');
            }
        prevScrollpos = currentScrollPos;
    }

    function refreshData($league) {
            // $(".d-flex").loading(); // 開始 loading
            $.ajax({
                method: 'POST',
                url: '/ajaxupdate',
                headers:{ 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {'league' :$league , 'page':1},
                success:function(data){
                        $("#datatable").html(data['datatable']);
                        $("#pagination").html(data['pagination']);
                        if($league=='ggg_tmpsc')
                            $("#choose_league").text('Legion');
                        else if($league=='ggg_tmphc')
                            $("#choose_league").text('HC Legion');
                        else if($league=='ggg_tmpssfsc')
                            $("#choose_league").text('SSF Legion');
                        else if($league=='ggg_tmpssfhc')
                            $("#choose_league").text('SSF HC Legion');

                        $("#choose_league").val($league);
                        document.getElementById("searchBox").value="";
                        // $(".d-flex").loading( "stop" ) // 停止 loading
                        // $('html, body').animate({ scrollTop: 0 }, 'slow');
                    },
                error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
        });
    }


    function changePage($page) {
            // $(".d-flex").loading(); // 開始 loading
            $("#searchBox").val('');
            $.ajax({
                method: 'POST',
                url: '/ajaxupdate',
                headers:{ 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {
                    'league' : $('.choose_league').text(),
                    'page':$page},
                success:function(data){
                        $("#datatable").html(data['datatable']);
                        // $(".d-flex").loading( "stop" ) // 停止 loading
                        // $('html, body').animate({ scrollTop: 0 }, 'slow');
                        if(!document.getElementById("pagination").value){
                            $("#pagination").html(data['pagination']);
                        }
                    },
                error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
            });
    }

$('#searchBox').keyup(function(event) {
    $page=document.getElementById("pagination").value;
    $url='/ajaxsearch';
    console.log('.on(change) = >' + $(this).val() + '<');
    if (event.keyCode === 13&&document.getElementById("searchBox").value.length>=3) {
        $.ajax({
                method: 'POST',
                url: $url,
                headers:{ 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                data: {
                'league' : document.getElementById("choose_league").value,
                'page':$page,
                'searchItem':document.getElementById("searchBox").value,},
                success:function(data){
                    $("#datatable").html(data['datatable']);
                    $("#pagination").html(data['pagination']);
                    // $(".d-flex").loading( "stop" ); // 停止 loading
                    // $('html, body').animate({ scrollTop: 0 }, 'slow');
                    // if(!document.getElementById("pagination").value){
                    //
                    //     }
                    },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
        }

});

    $(document).ready(function(){
        changePage(1);
        $("#choose_league").text('Legion');
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
