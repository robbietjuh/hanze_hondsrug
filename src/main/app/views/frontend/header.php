<!doctype html>
<html lang="nl">
<head>
    <title>De Hondsrug Incidentenmanagement</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/vendor.css" />
    <script src="//code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>

    <meta charset="utf-8">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">De Hondsrug Incidentenmanagementsysteem</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/"><i class="glyphicon glyphicon-lock"></i> <?=$this->data['username'];?></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron">
        <div class="container">
            <h1>Welkom, <?=$this->data['displayname'];?></h1>
            <p class="lead">Rechts kunt u een incident melden, links kunt u uw probleem oplossen</p>
        </div>
    </div>

    <div class="container">