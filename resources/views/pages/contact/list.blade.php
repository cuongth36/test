@extends('layouts.smart')
@section('title','Lien he')
@section('content')
    <!-- Contact info -->
    <section class="contact-info">
        <div class="container">
          <div class="contact-info-map">
            <div class="row">
              <div class="col-md-12">
                @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
              @endif
              </div>
              <div class="col-md-12">
                <div class="google-map">
                 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4429.349206532245!2d105.76386857225191!3d21.007709270130412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134535bb9b4d1e7%3A0x15ee41f4c2da49f3!2zMzkgUGjDuiDEkMO0LCBN4buFIFRyw6wsIFThu6sgTGnDqm0sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1600404103303!5m2!1svi!2s" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Touch with us -->
      <section class="touch-with-us padding-section">
        <div class="container">
          <div class="row">
            <div class="col-md-12 box-title">
              
              <h2 class="des-title des-title-pagecontact">Thông tin liên hệ</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 touch-with-us-form">
            <form class="form-touch-with-us" method="POST" action="{{route('contact.create')}}">
                @csrf
                <div class="form-row">
                  <div class="form-group col-md-4 form-group-custom">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Họ tên*">
                  </div>
                  <div class="form-group col-md-4 form-group-custom">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email*">
                  </div>
                  <div class="form-group col-md-4 form-group-custom">
                    <input type="text" class="form-control" id="subject" name="title" placeholder="Tiêu đề*">
                  </div>
                </div>
                <textarea class="form-control custom-textarea" placeholder="Nội dung*" name="content"></textarea>
                <button type="submit" class="newsletter-form-submit st2-btn-submit">
                  <span class="newsletter-submit-text st2-submit-text">Gửi</span>
                  <span class="newsletter-submit-icon st2-submit-icon">
                    <i class="lnr lnr-arrow-right"></i>
                  </span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>
@endsection