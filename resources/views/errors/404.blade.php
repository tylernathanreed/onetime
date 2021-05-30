@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', isset($exception) && !empty($exception->getMessage()) ? $exception->getMessage() : __('Not Found'))
