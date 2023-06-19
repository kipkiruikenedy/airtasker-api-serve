@extends('emails.commons.header')
<style>
    .container{
       border: 1px solid gray;
       border-radius: 5px;
       background-color: rgb(250, 244, 244);
       padding: 8px;
    }
    .title{
        text-align: center;
    }
    .userDetailsTitle{
        text-align: center;
    }
    .content{
        display: flex;
        justify-content: space-evenly;
        row-gap: 3;
    }
    .body{
        display: flex;
        flex-direction: column
    }
    .footer{
        display: flex;
        flex-direction: column;
        justify-content: center
    }
    </style>
<div class="container">
    <h1 class="title">Hello Admin</h1>
    <p>A new <span style="color: black; font-weight:900;"> {{ $role }} </span>  has successfully created an account in the system.</p>

    <h2 class="userDetailsTitle">User Details:</h2>
<div class="content">
<div class="body">
     <p>First Name:  {{ $firstName }}</p>  
      <p>Last Name:  {{ $lastName }}</p>
      <p>Phone Number:  {{ $phone}}</p>
</div>
<div class="body">
    <p>Email:  {{ $email }}</p>
    <p>Country:  {{ $country }}</p>
    
</div>      
</div>
 
</div>
<div class="footer">

    @extends('emails.commons.footer')
</div>
