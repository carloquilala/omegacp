$(document).ready(function(){
    var appContainer = $(".app-container");

  $('#omega-loader').fadeOut();
  $('.readmore').readmore({
    collapsedHeight: 60,
    embedCSS: true,
    lessLink: '<a href="#" class="readm-link">Read Less</a>',
    moreLink: '<a href="#" class="readm-link">Read More</a>',
  });

  $(".hamburger, .navbar-expand-toggle").on('click', function() {
    var outside = $('.fadetoblack');
    var hamburger = $('.hamburger');

      if ($(this).is('button')) {
        appContainer.toggleClass("expanded");
        $(this).toggleClass('is-active');
      } else {
        appContainer.removeClass("expanded");
        hamburger.toggleClass('is-active');
      }

      outside.on('click', function(){
        console.debug('clicked');
        outside.off('click');
        if (appContainer.hasClass('expanded')) {
            appContainer.removeClass('expanded');
            hamburger.removeClass('is-active');
            outside.off('click');
        }
      });
  });

  $('#sidebar-sticker').on('click', function(){
    $(this).toggleClass("is-active");
    if ($(this).hasClass("is-active")) {
      $.cookie("expandedMenu", 1);
      appContainer.toggleClass("expanded");
    } else {
      $.cookie("expandedMenu", 0);
      appContainer.toggleClass("expanded");
    }
  });

  $('select.select2').select2();

  $('.toggle-checkbox').bootstrapSwitch({
    size: "small"
  });

  $('.match-height').matchHeight();

  $('.datatable').DataTable({
    "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
  });

  $(".side-menu .nav .dropdown").on('show.bs.collapse', function() {
    return $(".side-menu .nav .dropdown .collapse").collapse('hide');
  });

  $(document).on('click', '.panel-heading a.panel-action[data-toggle="panel-collapse"]', function(e){
    e.preventDefault();
    var $this = $(this);

    // Toggle Collapse
    if(!$this.hasClass('panel-collapsed')) {
      $this.parents('.panel').find('.panel-body').slideUp();
      $this.addClass('panel-collapsed');
      $this.removeClass('omega-angle-down').addClass('omega-angle-up');
    } else {
      $this.parents('.panel').find('.panel-body').slideDown();
      $this.removeClass('panel-collapsed');
      $this.removeClass('omega-angle-up').addClass('omega-angle-down');
    }
  });

  //Toggle fullscreen
  $(document).on('click', '.panel-heading a.panel-action[data-toggle="panel-fullscreen"]', function (e) {
    e.preventDefault();
    var $this = $(this);
    if (!$this.hasClass('omega-resize-full')) {
      $this.removeClass('omega-resize-small').addClass('omega-resize-full');
    } else {
      $this.removeClass('omega-resize-full').addClass('omega-resize-small');
    }
    $this.closest('.panel').toggleClass('is-fullscreen');
  });

  $('.datepicker').datetimepicker();

  // Right navbar toggle
  $('.navbar-right-expand-toggle').on('click', function(){
    $('ul.navbar-right').toggleClass('expanded');
  }); 
});
