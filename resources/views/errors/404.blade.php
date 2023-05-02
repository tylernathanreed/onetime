@extends('errors::minimal')

@section('title', 'Not Found')
@section('code', '404')
@section('message', isset($exception) && ! empty($exception->getMessage()) ? $exception->getMessage() : 'Not Found')
