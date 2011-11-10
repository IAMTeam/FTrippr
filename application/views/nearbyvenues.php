<?php $this->load->view('shared/header',array('title'=>$title)); ?>

<menu>
    <button id="back" class="left">&lt Back</button>
    <a href="#">FieldTrippr</a>
    <button id="menu">Menu</button>
</menu>
<ul id="locations">
    <li>
        <figure class="category green-roof"></figure>
        <h2>Location Name Placeholder</h2>
        <p class="distance">0.5mi</p>
    </li>
    <li>
        <figure class="category park"></figure>
        <h2>Location Name Placeholder</h2>
        <p class="distance">3.5mi</p>
    </li>
    <li>
        <figure class="category museum"></figure>
        <h2>Location Name Placeholder</h2>
        <p class="distance">6.8mi</p>
    </li>
    <li>
        <figure class="category beach"></figure>
        <h2>Location Name Placeholder</h2>
        <p class="distance">10.1m</p>
    </li>
</ul>

<?php $this->load->view('shared/footer'); ?>