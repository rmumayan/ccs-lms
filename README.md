## How to use validation on form

What you need.
1. A Form with `novalidate` property and a unique `id`
2. A single submit button
3. bootstrap basic form


in HTML
```html
    <form id="login-form" method="post" novalidate>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" name="username" required="">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" id="password" name="password" required="">
		</div>
		<button type="submit" id="submit-btn" class="btn btn-success btn-block">Log in</button>
	</form>
```

in JavaScript
```javascript
	$("#login-form").on("submit", function(e) {
	  e.preventDefault();
	  submit_btn_animate($(this),true,'Loading ');
	  if (!validation_validate_form($(this))) {
	  	submit_btn_animate($(this),false,'Log in');
	  	return;
	  }
	  console.log($(this).serialize());	
	});
```




