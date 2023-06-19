@extends('emails.commons.header')

<style>
    .container {
        border: 1px solid gray;
        border-radius: 5px;
        background-color: rgb(250, 244, 244);
        padding: 8px;
    }

    .title {
        text-align: center;
    }

    .content {
        text-align: center;
        margin-top: 20px;
    }
</style>

<div class="container">
    <h1 class="title">Hello  {{$firstName}}</h1>
    <p>You requested to reset your password in airtaska.com account. If you did not authorize this, simply ignore this email.</p>
    
    <div class="content">
        <h2>Your One-Time Password (OTP) is:</h2>
        <h1>{{ $otp }}</h1>
        <p>Please use this OTP to reset your password on the password reset page.</p>
    </div>
</div>

@extends('emails.commons.footer')
