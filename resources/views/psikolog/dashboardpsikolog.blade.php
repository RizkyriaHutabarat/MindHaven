<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Psikolog</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body>
@extends('layouts.psikolog')

    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Welcome to the Dashboard</h2>
                <div>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle ml-3"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Total Users
                    </div>
                    <div class="card-body">
                        <h5>1,234</h5>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Active Sessions
                    </div>
                    <div class="card-body">
                        <h5>56</h5>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        New Messages
                    </div>
                    <div class="card-body">
                        <h5>12</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Content -->
        <div class="row">
            <div class="col-md-6">
                <!-- Chart or Additional Widgets Here -->
                <div class="card">
                    <div class="card-header">
                        Sales Overview
                    </div>
                    <div class="card-body">
                        <!-- Add a chart here -->
                        <p>Chart goes here</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Activity Log or Other Content -->
                <div class="card">
                    <div class="card-header">
                        Recent Activities
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>User1 logged in</li>
                            <li>User2 sent a message</li>
                            <li>User3 updated profile</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
