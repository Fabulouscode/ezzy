// Iterate over each select element
$('select').each(function () {
    

    // Cache the number of options
    var $this = $(this),
        numberOfOptions = $(this).children('option').length;

    // Hides the select element
    $this.addClass('s-hidden');

    // Wrap the select element in a div
    $this.wrap('<div class="custom_select" ></div>');

    // console.log($this);
    // console.log($this.attr('id'));
    // debugger;
    // Insert a styled div to sit over the top of the hidden select element
    $this.after('<div class="select form-control searchByCountry" id="custom_search_by_county" name="'+$this.attr('name')+'"></div>');
// console.log();
    // Cache the styled div
    var $styledSelect = $this.next('div.select');

    // Show the first select option in the styled div
    var selectedOption = $this.find('option:selected');
    $styledSelect.text(selectedOption.text());

    // Insert an unordered list after the styled div and also cache the list
    var $list = $('<ul />', {
        'class': 'custom-select-dropdown'
    }).insertAfter($styledSelect);

    // Insert a list item into the unordered list for each select option
    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children('li');

    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function (e) {
        // console.log(1);
        e.stopPropagation();
        $('div.select.active').each(function () {
            $(this).removeClass('active').next('ul.custom-select-dropdown').hide();
        });
        $(this).toggleClass('active').next('ul.custom-select-dropdown').toggle();
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function (e) {
        // console.log(2);
        e.stopPropagation();
        let id = $this.attr("id");
        // console.log($(this).attr('rel'));


        $(document).find( `#${id} option[value=`+$(this).attr('rel')+ ']').attr('selected','selected');
        $this.change();
        // $('#approvedSearchByCountry').val($(this).attr('rel'));
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.hide();
        /* alert($this.val()); Uncomment this for demonstration! */
    });

    // Hides the unordered list when clicking outside of it
    $(document).click(function () {
        console.log(3);
        $styledSelect.removeClass('active');
        $list.hide();
    });

});
