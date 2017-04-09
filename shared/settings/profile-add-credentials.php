<div id="add-credentials">
    <h3>Credential</h3>
    <hr>
    <div class="row">

    
        <div id="username-holder" class="col-md-7 relative-div">
            <label for="username">Username</label>
            <div class="input-group ">
                <input type="text"  class="form-control" title="Username must contain atleast 6 ALPHA NUMERIC characters." pattern="[a-z0-9._%+-]{6,}$" id="username" name="username" value="" placeholder="Username" required>
                <span class="input-group-addon" id="basic-addon2">@lspu.com</span>
            </div>
            <div id="validated" data="0" class="absolute-div">
                <!---->
            </div>
            <br>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-7">
            <div class="well well-sm">The default password is: <?php echo User::$default_password?></div>
        </div>
    </div>
</div>
