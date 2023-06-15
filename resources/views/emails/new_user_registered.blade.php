@extends('emails.commons.header')

<div class="container">
    <h1>Hello Admin</h1>
    <p>A new  {{ $role }} has successfully created an account in the system.</p>

    <h2>User Details:</h2>

    <p>First Name: {{ $firstName }}</p>
    <p>Last Name: {{ $lastName }}</p>
    <p>Email: {{ $email }}</p>
</div>

@extends('emails.commons.footer')
