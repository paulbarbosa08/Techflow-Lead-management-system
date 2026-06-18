@extends('layouts.app')

@section('landing-content')

<nav class="landing-nav">
    <div class="landing-nav-brand">
        <i data-lucide="sparkles"></i>
        <div class="landing-nav-brand-text">
            <span class="landing-nav-brand-name">TECHFLOW</span>
            <span class="landing-nav-brand-subtitle">Lead Management</span>
        </div>
    </div>
    <div class="landing-nav-actions">
        <a href="{{ route('login.show') }}" class="landing-nav-btn">Login</a>
    </div>
</nav>

<section class="landing-hero">
    <h1>Turn every <span>lead</span> into a closed deal</h1>
    <p>TECHFLOW helps your team track, assign, and follow up on leads in one place, so nothing slips through the cracks and every prospect gets the attention they deserve.</p>
    <div class="landing-hero-actions">
        <a href="{{ route('login.show') }}" class="landing-btn-primary">Login to Your Account</a>
    </div>
</section>

<section class="landing-section">
    <div class="landing-section-header">
        <h2>What you can do with TECHFLOW</h2>
        <p>A complete toolkit for managing leads from first contact to closed deal.</p>
    </div>
    <div class="landing-features-grid">
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="users"></i></div>
            <h3>Lead Assignment</h3>
            <p>Admins create and assign leads directly to staff, so everyone always knows what they're responsible for.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="search"></i></div>
            <h3>Search & Filter</h3>
            <p>Quickly find any lead by name, company, or status instead of scrolling through long lists.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="flag"></i></div>
            <h3>Priority Levels</h3>
            <p>Tag leads as Low, Medium, or High priority so your team always knows what to tackle first.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="history"></i></div>
            <h3>Activity Logs</h3>
            <p>Every status change and assignment is automatically tracked, giving you a full audit trail.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="sparkles"></i></div>
            <h3>AI Note Summaries</h3>
            <p>Instantly summarize a lead's entire note history into one clear paragraph with a single click.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="trophy"></i></div>
            <h3>Staff Leaderboard</h3>
            <p>See which team members are converting the most leads, ranked by performance.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="alert-triangle"></i></div>
            <h3>Stale Lead Alerts</h3>
            <p>Get notified automatically when a lead has gone untouched for too long.</p>
        </div>
        <div class="landing-feature-card">
            <div class="landing-feature-icon"><i data-lucide="mail"></i></div>
            <h3>Email Notifications</h3>
            <p>Staff get notified by email the moment a new lead is assigned to them.</p>
        </div>
    </div>
</section>

<section class="landing-about-wrapper">
    <div class="landing-about">
        <h2>About TECHFLOW</h2>
        <p>TECHFLOW was built to solve a simple but common problem: leads getting lost in spreadsheets, group chats, and scattered notes. We wanted a single place where admins and sales staff could collaborate on every lead from the moment it comes in to the moment it's won or lost, with full visibility into who's doing what and how well the team is performing.</p>
    </div>
</section>

<section class="landing-cta">
    <h2>Already using TECHFLOW?</h2>
    <p>Log in to your account and start managing your leads.</p>
    <a href="{{ route('login.show') }}" class="landing-btn-primary">Login Now</a>
</section>

<footer class="landing-footer">
    &copy; {{ date('Y') }} TECHFLOW Lead Management System. All rights reserved.
</footer>

@endsection