<h1>EzzyCare</h1>


<h2>Deploy the project stepwise</h2>

<h3>Step:1</h3>
<h4>Run:- Composer Install</h4>

<h3>Step:2</h3>
<h4>
Run:- cp .env.example .env<br>
Run:- php artisan key:generate
</h4>

<h3>Step:3</h3>
<h4>
Run:- npm install<br>
Run:- npm run dev
</h4>

<h3>Step:5</h3>
<h4>Run:- php artisan migrate --seed</h4>