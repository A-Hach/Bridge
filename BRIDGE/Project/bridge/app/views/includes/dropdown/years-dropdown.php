<div class="c-dropdown noselect years-dropdown has-input">
    <span class="c-dropdown-toggle">
        <div class="iconed-input">
            <input type="text" value="<?php echo isset($year) ? $year : ''; ?>" name="year" class="input noselect" placeholder="Année" readonly>
            <svg class="input-icon" width=" 12" height="20" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.75999 6L4.59 2.83L1.42 6L0 4.59L4.59 0L9.17 4.59L7.75999 6Z" fill="black" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.41 12L4.58 15.17L7.75 12L9.17 13.41L4.58 18L-1.90735e-06 13.41L1.41 12Z" fill="black" />
            </svg>
        </div>
    </span>
    <ul class="c-dropdown-items <?php echo $dropdown_dir; ?>-dropdown c-scrollable" data-limit="">
    </ul>
    <script>
        let currentYear = new Date().getFullYear();

        for (let i = 1900; i <= currentYear; i++)
            $('.c-dropdown.years-dropdown .c-dropdown-items').append('<li data-value="' + (i) + '"><div class="pd-y-5 pd-x-10">' + (i) + '</div></li>');
    </script>
</div>