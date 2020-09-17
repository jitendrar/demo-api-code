@if(Session::has('success_message'))
<div class="flash-msg">
    <div class="container">
        
        <div class='custom-alerts alert alert-success fade in'>
                {{ Session::get('success_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        </div>  
    </div>
</div>    
@endif

@if(Session::has('error_message'))
<div class="flash-msg">
    <div class="container">
    <div class='custom-alerts alert alert-danger fade in'>
            {{ Session::get('error_message')}}
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
    </div>
    </div>
</div>    
@endif