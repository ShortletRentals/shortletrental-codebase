@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');
    // dd(gettype($auth_user));
    if (isset($auth_user) && $is_guest != 1) {
      $userId = $auth_user->data->id;
    }else{
       $userId = $data->guest_id;
    }
    // dd($data->getProperty);
    // dd($data['featuredProperty']);
?>
@section('content')
<style>
	.column-xs-12,
.column-sm-12,
.column-md-12,
.column-lg-12,
.column-xs-11,
.column-sm-11,
.column-md-11,
.column-lg-11,
.column-xs-10,
.column-sm-10,
.column-md-10,
.column-lg-10,
.column-xs-9,
.column-sm-9,
.column-md-9,
.column-lg-9,
.column-xs-8,
.column-sm-8,
.column-md-8,
.column-lg-8,
.column-xs-7,
.column-sm-7,
.column-md-7,
.column-lg-7,
.column-xs-6,
.column-sm-6,
.column-md-6,
.column-lg-6,
.column-xs-5,
.column-sm-5,
.column-md-5,
.column-lg-5,
.column-xs-4,
.column-sm-4,
.column-md-4,
.column-lg-4,
.column-xs-3,
.column-sm-3,
.column-md-3,
.column-lg-3,
.column-xs-2,
.column-sm-2,
.column-md-2,
.column-lg-2,
.column-xs-1,
.column-sm-1,
.column-md-1,
.column-lg-1 {
    padding-left: 12.5px;
    padding-right: 12.5px;
    position: relative;
    min-height: 1px;
    float: left;
}

.column-xs-12 {
    width: 100%;
}
.column-xs-11 {
    width: 91.66667%;
}
.column-xs-10 {
    width: 83.33333%;
}
.column-xs-9 {
    width: 75%;
}
.column-xs-8 {
    width: 66.66667%;
}
.column-xs-7 {
    width: 58.33333333%;
}
.column-xs-6 {
    width: 50%;
}
.column-xs-5 {
    width: 41.66666667%;
}
.column-xs-4 {
    width: 33.333%;
}
.column-xs-3 {
    width: 25%;
}
.column-xs-2 {
    width: 16.66667%;
}
.column-xs-1 {
    width: 8.33333%;
}
.line {
    margin-left: -12.5px;
    margin-right: -12.5px;
}
.line:after {
    content: '';
    display: block;
    clear: both;
}

@media (min-width: 768px) {
    .column-sm-12 {
        width: 100%;
    }
    .column-sm-11 {
        width: 91.66667%;
    }
    .column-sm-10 {
        width: 83.33333%;
    }
    .column-sm-9 {
        width: 75%;
    }
    .column-sm-8 {
        width: 66.66667%;
    }
    .column-sm-7 {
        width: 58.33333333%;
    }
    .column-sm-6 {
        width: 50%;
    }
    .column-sm-5 {
        width: 41.66666667%;
    }
    .column-sm-4 {
        width: 33.333%;
    }
    .column-sm-3 {
        width: 25%;
    }
    .column-sm-2 {
        width: 16.66667%;
    }
    .column-sm-1 {
        width: 8.33333%;
    }
}

@media (min-width: 992px) {
    .column-md-12 {
        width: 100%;
    }
    .column-md-11 {
        width: 91.66667%;
    }
    .column-md-10 {
        width: 83.33333%;
    }
    .column-md-9 {
        width: 75%;
    }
    .column-md-8 {
        width: 66.66667%;
    }
    .column-md-7 {
        width: 58.33333333%;
    }
    .column-md-6 {
        width: 50%;
    }
    .column-md-5 {
        width: 41.66666667%;
    }
    .column-md-4 {
        width: 33.333%;
    }
    .column-md-3 {
        width: 25%;
    }
    .column-md-2 {
        width: 16.66667%;
    }
    .column-md-1 {
        width: 8.33333%;
    }
}

@media (min-width: 1200px) {
    .column-lg-12 {
        width: 100%;
    }
    .column-lg-11 {
        width: 91.66667%;
    }
    .column-lg-10 {
        width: 83.33333%;
    }
    .column-lg-9 {
        width: 75%;
    }
    .column-lg-8 {
        width: 66.66667%;
    }
    .column-lg-7 {
        width: 58.33333333%;
    }
    .column-lg-6 {
        width: 50%;
    }
    .column-lg-5 {
        width: 41.66666667%;
    }
    .column-lg-4 {
        width: 33.333%;
    }
    .column-lg-3 {
        width: 25%;
    }
    .column-lg-2 {
        width: 16.66667%;
    }
    .column-lg-1 {
        width: 8.33333%;
    }
}
#acepto_valorar {
    color: #3F3F3F;
    font-size: 12px;
    font-weight: 600;
    line-height: 19px;
    margin: 30px 0;
}

#all main {
    margin-top: 0;
}

#background {
    background-image: url(/default/imagenes/slider.jpg);
    width: 100%;
    background-size: cover;
    height: 82px;
    position: relative;
    z-index: 0;
}

#background .overlay {
    background-color: rgba(76, 76, 76, 0.9);
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 5;
}

.bloque-valoracion-general>div {
    display: inline-block;
    vertical-align: middle;
    margin-right: 10px;
}

.border-radio {
    height: 23px;
    width: 23px;
    border: 1px solid #AFAFB0;
    display: inline-block;
    border-radius: 50%;
    cursor: pointer;
}

.border-radio input {
    display: none;
}

.bg-radio {
    height: 15px;
    width: 15px;
    border-radius: 50%;
    display: inline-block;
    margin-top: 3px;
}

.bg-radio.select {
    background-color: #00DC8F;
}

#bloque_p textarea,
#bloque_n textarea {
    height: 150px;
}

input#botonReserva {
    color: #FFFFFF;
    font-size: 18px;
    font-weight: bold;
    line-height: 19px;
    padding: 15px 80px;
    text-transform: uppercase;
    float: none !important;
}

div#botonReserva a {
    color: #FFF;
    padding: 14px 35px;
    margin-top: 10px;
    display: inline-block;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
    text-decoration: none;
}

.boton_comentario {
    background-color: transparent !important;
    text-align: center;
    margin-top: 55px;
}

#button-intranet {
    position: absolute;
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
    color: #ffffff;
    z-index: 30;
    margin-top: 30px;
    text-decoration: none;
}

#contenedor-top {
    max-width: 1165px;
    width: 100%;
    height: 100%;
    margin: 0 auto;
    text-align: center;
    display: table;
}

#confirmacion-valoracion {
    max-width: 550px;
    width: 100%;
    margin: 90px auto;
}

#contenedor {
    background-color: #FAFBFF;
    overflow: hidden;
}

#contenido {
    width: 100%;
    max-width: 1165px;
    margin: 45px auto;
    border: 1px solid #CCD2E2;
    background-color: #FFFFFF;
    padding: 70px !important;
}

#contenido.resultado-valoraciones {
    background: transparent;
    padding: 0 !important;
    border: 0;
}

.description-accommodation {
    padding-top: 25px;
}

.description-accommodation span {
    color: #384A54;
    font-family: "Open Sans";
    font-size: 15px;
    line-height: 18px;
}

#FAceptoCondicionesValoracion {
    vertical-align: middle;
    width: 18px !important;
    height: 18px !important;
}

.icon-back {
    margin-right: 10px;
}

.icon-back:before {
    font-size: 22px;
    vertical-align: middle;
}

.icon-star {
    color: #AFAFB0;
}

.icon-star-filled {
    color: #57646C;
}

.icon-envelope {
    font-size: 110px;
    color: #C8C8C8;
}

.icon-envelope:before {
    transform: rotate(100deg);
    -webkit-transform: rotate(-45deg);
}

.name-accommodation {
    color: #2D414C;
    font-family: "Playfair Display";
    font-size: 25px;
    line-height: 24px;
}

#nota {
    position: relative;
    opacity: 0;
    font-size: 12px;
    font-weight: bold;
    line-height: 14px;
    text-align: center;
    border: 1px solid;
    padding: 10px 30px;
    margin-left: 20px;
}

.nota-1 {
    background-color: #ffcdcd;
    color: #c42422;
    border-color: #c42422;
}

.nota-2 {
    background-color: #ffddc1;
    color: #da7d16;
    border-color: #da7d16;
}

.nota-3 {
    background-color: #fbebbb;
    color: #ddac16;
    border-color: #ddac16;
}

.nota-4 {
    background-color: #f0f2bd;
    color: #bbbf2a;
    border-color: #bbbf2a;
}

.nota-5 {
    background-color: #dcf4d7;
    color: #50c938;
    border-color: #50c938;
}

#valoracion-caracteristicas .icon-super-sad.select {
    color: #c42422;
}

#valoracion-caracteristicas .icon-sad.select {
    color: #da7d16;
}

#valoracion-caracteristicas .icon-regular.select {
    color: #ddac16;
}

#valoracion-caracteristicas .icon-happy.select {
    color: #bbbf2a;
}

#valoracion-caracteristicas .icon-super-happy.select {
    color: #50c938;
}

.nota-1:after {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #ffcdcd;
}

.nota-2:after {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #ffddc1;
}

.nota-3:after {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #fbebbb;
}

.nota-4:after {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #f0f2bd;
}

.nota-5:after {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #dcf4d7;
}

.nota-1:before {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #c42422;
}

.nota-2:before {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #da7d16;
}

.nota-3:before {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #ddac16;
}

.nota-4:before {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #bbbf2a;
}

.nota-5:before {
    border: solid transparent;
    border-color: transparent;
    border-right-color: #50c938;
}


#nota:after,
#nota:before {
    right: 100%;
    top: 50%;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

#nota:after {
    border-width: 6px;
    margin-top: -6px;
}

#nota:before {
    border-width: 7px;
    margin-top: -7px;
}

#simbPositivo,
#simbNegativo {
    display: inline;
    position: relative;
}

#simbPositivo i {
    border-radius: 50%;
    background: #19DC8C;
    color: #FFF;
    font-size: 11px;
    vertical-align: middle !important;
    padding: 6px 6px;
    margin-right: 5px !important;
}

#simbNegativo i {
    font-size: 22px;
    vertical-align: middle !important;
    color: #31BFD8;
}

.texto-ok {
    font-weight: bold;
}

.title-accommodation span {
    display: block;
}

.type-accommodation {
    color: #6C7B82;
    font-size: 12px;
    font-weight: bold;
    line-height: 29px;
    text-transform: uppercase;
}

.title-border {
    border-top: 1px solid #E2E2E2;
    width: 100%;
    display: inline-block;
    margin: 20px 0 28px;
}

.title-form {
    color: #2D414C;
    font-family: "Playfair Display";
    font-size: 28px;
    line-height: 27px;
}

.title-list {
    color: #2D414C;
    font-size: 18px;
    font-weight: bold;
    line-height: 27px;
    padding: 60px 0 20px;
    display: block;
}

.title-puntuar {
    text-transform: uppercase;
    color: #3F3F3F;
    font-size: 12px;
    font-weight: bold;
    line-height: 19px;
}

.title-reviews {
    padding: 0;
    border-bottom: 0px;
    text-align: center;
    color: #fff;
    font-size: 3rem;
    font-weight: normal;
    font-family: 'Playfair Display', serif !important;
    display: table-cell;
    vertical-align: middle;
    position: relative;
    z-index: 10;
}

.title-notas {
    font-weight: bold;
    color: #3F3F3F;
    font-size: 11px;
    line-height: 19px;
    text-transform: uppercase;
}

.title-input {
    color: #3F3F3F;
    font-size: 11px;
    font-weight: bold;
    line-height: 19px;
    text-transform: uppercase;
}

#TituloValoracion,
#contenido textarea {
    width: 100%;
    border: 0;
    border-bottom: 1px solid #E6E6E6;
    color: #494949;
    font-family: "Playfair Display";
    font-size: 19px;
    line-height: 26px;
    padding-top: 20px;
}

#TituloValoracion {
    padding-bottom: 15px;
}

#TituloValoracion:focus,
#contenido textarea:focus {
    outline: none;
}

#valoracion-general .icon {
    cursor: pointer;
    float: left;
    padding: 0 2px;
    font-size: 25px;
    vertical-align: middle;
}

#valoracion-caracteristicas {
    width: 100%;
}

#valoracion-caracteristicas .title-caracteristicas {
    text-align: left;
    color: #3F3F3F;
    font-size: 15px;
    font-weight: 600;
    line-height: 19px;
}

#valoracion-caracteristicas tr {
    border-bottom: 1px solid #E6E6E6;
    position: relative;
}

#valoracion-caracteristicas td {
    text-align: center;
    padding: 10px 0;
}

#valoracion-caracteristicas .icon {
    display: none;
    font-size: 42px;
    color: #c8c8c8;
}

#error-valoracion {
    background-color: #FFFFFF;
    max-width: 500px;
    margin: 60px auto;
    -webkit-box-shadow: 0 5px 45px 0 rgba(0, 0, 0, .3);
    box-shadow: 0 5px 45px 0 rgba(0, 0, 0, .3);
    border: 0px;
    padding: 30px 30px 30px 30px !important;
    text-align: center;
}

#error-valoracion>div {
    margin-top: 30px;
    margin-bottom: 30px;
}

#error-valoracion label {
    margin-top: 30px;
    font-weight: normal;
    text-align: center;
}

.warning {
    margin: 0 auto;
    width: 135px;
    height: 135px;
    text-align: center;
    vertical-align: middle;
    line-height: 1em;
    padding: 0px;
    border-radius: 50%;
    color: #f6f6f8;
    font-weight: bold;
    font-size: 118px;
    border: 6px solid #f6f6f8;
}

@media (max-width: 1165px) {
    #contenedor {
        background-color: #FFFFFF;
    }

    #contenido {
        padding: 30px !important;
        background-color: transparent;
        border: 0;
        margin: 20px auto;
    }
}

@media (max-width: 768px) {
    #button-intranet {
        display: none;
    }

    #bloque_p,
    #bloque_n {
        margin-bottom: 50px;
    }

    #botonReserva a {
        padding: 14px 22px !important;
    }

    #contenido {
        padding: 15px !important;
    }

    #contenido .icon-envelope::before {
        margin-bottom: 40px;
    }

    #confirmacion-valoracion {
        text-align: center;
    }

    .img-accommodation img {
        width: 100%;
        margin-bottom: 20px;
        display: block;
    }
}

@media (max-width: 500px) {
    #botonReserva {
        padding: 15px 40px !important;
        width: 100%;
        text-align: center;
    }

    .border-radio {
        border: 0;
        width: 42px;
        height: 42px;
    }

    .bloque-valoracion-general>div {
        display: block;
        text-align: center;
        overflow: hidden;
        margin-left: 0 !important;
        margin-right: 0;
        margin-bottom: 10px;
    }

    #nota {
        font-size: 18px;
        border: 1px solid;
        padding: 18px 30px;
    }

    #valoracion-general .icon {
        float: none;
        font-size: 42px;
        padding: 0 5px;
    }

    .title-notas {
        display: none;
    }

    .title-list {
        padding: 50px 0 20px;
    }
    #valoracion-caracteristicas .icon {
        display: block;
    }

    #valoracion-caracteristicas td {
        padding-top: 85px;
        padding-bottom: 45px;
    }

    #valoracion-caracteristicas .title-caracteristicas {
        position: absolute;
        width: auto;
        padding: 24px 15px 15px 0px;
        font-family: "Playfair Display";
        font-size: 20px;
        font-weight: normal;
    }
}
#all main input#botonReserva:active {
    box-shadow: inset 0 3px 5px #da3e0e;
}#all main input#botonReserva:hover {
    background-color: #ef4612 !important;
}



#botonReserva:active {
    box-shadow: inset 0 3px 5px #da3e0e;
}
#botonReserva {
    background: #f1592a !important;
    border: 1px solid #da3e0e !important;
    color: #fff !important;
    text-shadow: 1px 1px #ef4612;
}
.radio_css {
    padding-top: 0 !important;
    padding-bottom: 25px !important;
    text-align: center;
    margin: 0 auto;
}

.radio_css label.custom_radio_b {
    text-align: center;
    margin: 0 auto;
    display: table;
}
.parsley-error{
	border:none !important;
}
</style>
    <main>
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			<!-- <div class="sidebar_l">
		        			<div class="sidebar-link">
		        				<ul>
		        					<li><a href="#">My Account</a></li>
		        					<li><a href="#">My Cards</a></li>
		        					<li><a href="#" class="active">My Bookings</a></li>
		        					<li><a href="#">My Favorites</a></li>
		        					<li><a href="#">Notification</a></li>
		        					<li><a href="#">Logout</a></li>
		        				</ul>
		        			</div>
		        		</div> -->
	        			<div id="contenido">
        <div class="line">
            <div class="column-xs-12">
                <span class="title-form">Describe your stay:</span>
            </div>
            <span class="title-border"></span>
        </div>
        <div class="line">
            <div class="column-xs-12">
            <div class="line">
    <div class="column-sm-4 column-xs-12">
        <span class="img-accommodation">
            <img src="{{ $data->getProperty->image }}" alt="{{ $data->getProperty->title }}" title="{{ $data->getProperty->title }}">
        </span>
    </div>


    <div class="column-sm-8 column-xs-12">
        <div class="title-accommodation">
            <span class="name-accommodation">{{ $data->getProperty->title }}</span>
            <span class="type-accommodation">Apartment| {!! $data->getProperty->getPropertyAddress[0]->address !!}</span>
        </div>
        <div class="description-accommodation">
            <span>
			{!! $data->getProperty->description !!}
			
			</span>
        </div>
    </div>


	@if($data->is_rating==0)
</div>            <form name="formValoraciones" id="formValoraciones"method="POST" action={{url('booking-rating-submit')}}>
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <input type="Hidden" name="booking_id" value="{!! $data->booking_id !!}">
                <input type="Hidden" name="user_id" value="{{$userId}}">
				<input type="Hidden" name="property_id" value="{{$data->getProperty->id}}">
                <input type="Hidden" name="bk" value="bk_shortlet" id="bk">
                <input type="Hidden" name="cvc" id="cvc" value="ED4BBD161EFD8622E9551402434FD6B63994A1E3">
                <input type="Hidden" name="localizador" id="localizador" value="18011519-1627493049">
                <input type="Hidden" name="validar" id="validar" value="1">
                <input type="Hidden" name="mostrarI" id="mostrarI" value="0">
                <input type="Hidden" name="validarI" id="validarI" value="1">
                <input type="hidden" name="ValValoracionG" id="ValValoracionG" value="0">
                <input type="hidden" name="nombreOcupante" id="nombreOcupante" value="Flora">
                <input type="hidden" name="tipoRelacion" id="tipoRelacion" value="0">
                <div class="line">
                    <div class="column-xs-12">
                        <span class="title-list">1. Your general rating of this accommodation:</span>
                    </div>
                    <div class="column-xs-12">
                        <div class="bloque-valoracion-general" onmouseout="resetValoracionGeneral();">
                            <div id="cartel">
                                <div class="title-puntuar">Select your rating on a scale of 1 to 5:</div>
                            </div>
                            <div id="valoracion-general">
							<div class="review-option stars">
                              <input class="star star-5" value="5" id="star-5-2"  data-parsley-required="true" type="radio" name="star" data-parsley-multiple="star">
                              <label class="star star-5" for="star-5-2"></label>
                              <input class="star star-4" value="4" id="star-4-2" data-parsley-required="true" type="radio" name="star" data-parsley-multiple="star">
                              <label class="star star-4" for="star-4-2"></label>
                              <input class="star star-3" value="3" id="star-3-2" data-parsley-required="true" type="radio" name="star" data-parsley-multiple="star">
                              <label class="star star-3" for="star-3-2"></label>
                              <input class="star star-2" value="2" id="star-2-2" data-parsley-required="true" type="radio" name="star" data-parsley-multiple="star">
                              <label class="star star-2" for="star-2-2"></label>
                              <input class="star star-1" value="1" id="star-1-2" checked data-parsley-required="true" type="radio" name="star" data-parsley-multiple="star">
                              <label class="star star-1" for="star-1-2"></label>
                            </div>
                            </div>
                            <div id="nota">-</div>
                        </div>
                    </div>
                </div>
                <div class="line">
                    <div class="column-xs-12">
                        <span class="title-list">2. Rate the following:</span>
                    </div>
                    <div class="column-xs-12">
                        <table id="valoracion-caracteristicas">
                            <tbody><tr class="title-notas">
                                <td></td>
                                <td>Very bad</td>
                                <td>Bad</td>
                                <td>Average</td>
                                <td>Good</td>
                                <td>Excellent</td>
                            </tr>
                                                            <tr>
                                    <input type="hidden" name="idCaracteristica1" id="idCaracteristica1" value="1">
                                    <input type="hidden" name="nombre_caracteristica_1" id="nombre_caracteristica_1" value="Service">
                                    <td class="title-caracteristicas">Service</td>

                            
									<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="service" value="1" checked data-parsley-required="true" data-parsley-multiple="service">
												<span class="checkmark"></span>
											</label>
                                        </td>

										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="service" value="2" data-parsley-required="true" data-parsley-multiple="service">
												<span class="checkmark"></span>
											</label>
                                        </td>

										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="service" value="3" data-parsley-required="true" data-parsley-multiple="service">
												<span class="checkmark"></span>
											</label>
                                        </td>

										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="service" value="4" data-parsley-required="true" data-parsley-multiple="service">
												<span class="checkmark"></span>
											</label>
                                        </td>

										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="service" value="5" data-parsley-required="true" data-parsley-multiple="service">
												<span class="checkmark"></span>
											</label>
                                        </td>
</tr>
<tr>
                                    <input type="hidden" name="idCaracteristica2" id="idCaracteristica2" value="2">
                                    <input type="hidden" name="nombre_caracteristica_2" id="nombre_caracteristica_2" value="Cleanliness">
                                    <td class="title-caracteristicas">Cleanliness</td>

                            

									<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="cleanliness" value="1" checked data-parsley-required="true" data-parsley-multiple="cleanliness">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="cleanliness" value="2" data-parsley-required="true" data-parsley-multiple="cleanliness">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="cleanliness" value="3" data-parsley-required="true" data-parsley-multiple="cleanliness">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="cleanliness" value="4" data-parsley-required="true" data-parsley-multiple="cleanliness">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="cleanliness" value="5" data-parsley-required="true" data-parsley-multiple="cleanliness">
												<span class="checkmark"></span>
											</label>
                                        </td>

                                        
                                                                       </tr><tr>
                                    <input type="hidden" name="idCaracteristica3" id="idCaracteristica3" value="3">
                                    <input type="hidden" name="nombre_caracteristica_3" id="nombre_caracteristica_3" value="Accommodation">
                                    <td class="title-caracteristicas">Accommodation</td>

                            
									<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="accommodation" value="1" checked data-parsley-required="true" data-parsley-multiple="accommodation">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="accommodation" value="2" data-parsley-required="true" data-parsley-multiple="accommodation">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="accommodation" value="3" data-parsley-required="true" data-parsley-multiple="accommodation">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="accommodation" value="4" data-parsley-required="true" data-parsley-multiple="accommodation">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="accommodation" value="5" data-parsley-required="true" data-parsley-multiple="accommodation">
												<span class="checkmark"></span>
											</label>
                                        </td>

                                       
                                                            </tr><tr>
                                    <input type="hidden" name="idCaracteristica4" id="idCaracteristica4" value="4">
                                    <input type="hidden" name="nombre_caracteristica_4" id="nombre_caracteristica_4" value="Location">
                                    <td class="title-caracteristicas">Location</td>

									<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="location" value="1" checked data-parsley-required="true" data-parsley-multiple="location">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="location" value="2" data-parsley-required="true" data-parsley-multiple="location">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="location" value="3" data-parsley-required="true" data-parsley-multiple="location">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="location" value="4" data-parsley-required="true" data-parsley-multiple="location">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="location" value="5" data-parsley-required="true" data-parsley-multiple="location">
												<span class="checkmark"></span>
											</label>
                                        </td>										
                                    </tr>

									<tr>
                                    <input type="hidden" name="idCaracteristica5" id="idCaracteristica5" value="5">
                                    <input type="hidden" name="nombre_caracteristica_5" id="nombre_caracteristica_5" value="Value for money">
                                    <td class="title-caracteristicas">Value for money</td>

                            
									<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="valueformoney" value="1" checked data-parsley-required="true" data-parsley-multiple="valueformoney">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="valueformoney" value="2" data-parsley-required="true" data-parsley-multiple="valueformoney">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="valueformoney" value="3" data-parsley-required="true" data-parsley-multiple="valueformoney">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="valueformoney" value="4" data-parsley-required="true" data-parsley-multiple="valueformoney">
												<span class="checkmark"></span>
											</label>
                                        </td>
										<td valign="center" class="radio_css">
											<label class="custom_radio_b">
												<input type="radio" name="valueformoney" value="5" data-parsley-required="true" data-parsley-multiple="valueformoney">
												<span class="checkmark"></span>
											</label>
                                        </td>

                                        
                            
                        </tr></tbody></table>
                    </div>
                </div>
                <div class="line">
                    <div class="column-xs-12">
                        <span class="title-list">3. Review Title:</span>
                    </div>
                    <div class="column-xs-12">
                        <span class="title-input">Write the title here:</span>
                        <input name="review" data-parsley-required="true" id="TituloValoracion" type="text" value="" placeholder="Write a title ...">
                    </div>
                </div>
                <div class="line">
                    <div class="column-xs-12">
                        <span class="title-list">4. Write more:</span>
                    </div>
                    <div class="column-xs-12">
                        <div class="line">
                            <div id="bloque_p" class="column-sm-6 column-xs-12">
                                <div class="textoComen">
                                    <div id="simbPositivo"><i class="fa fa-check" aria-hidden="true"></i></div>
                                    <span class="title-input">What did you like?</span>
                                </div>
                                <textarea placeholder="Leave a comment ..." maxlength="1500"  name="comment" id="comentario_positivo" type="text" value=""></textarea>
                            </div>
                            <div id="bloque_n" class="column-sm-6 column-xs-12">
                                <div class="textoComen">
                                    <div id="simbNegativo"><i class="fa fa-edit"></i></div>
                                    <span class="title-input">What can we improve?</span>
                                </div>
                                <textarea placeholder="Leave a comment ..." maxlength="1500"  name="improvement" id="comentario_negativo" type="text" value=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="line">
                    <div class="column-xs-12">
                        <div id="acepto_valorar">
                            <input name="accipt"  data-parsley-required="true" id="FAceptoCondicionesValoracion" type="checkbox" class="checkbox2" value="si">&nbsp;&nbsp;I accept the                              <a href="#" title="General terms">
                                <u onmouseover="Tip('<div id=\'condicionesV\'><b>Review Submission Conditions</b><br><br>Your review of this accommodation is based on your personal experience and there is no personal or business connection.<br><br>You may not post obscene or distasteful content, profanity or spiteful remarks.<br><br>We reserve the right not to publish any review which we may deem inappropriate.<br>By submitting your review, you accept these conditions.</div>',WIDTH,550,PADDING,10)">review submission conditions</u>
                            </a>
                        </div>
                    </div>
                    <div class="column-xs-12">
                        <div class="boton_comentario" style="background: rgb(57, 62, 71);">
                            <input type="submit" id="botonReserva" value="Submit Review" style="float: left;max-width: 100%;">
                        </div>
                    </div>
                </div>
            </form>
			@else
				<h2 ><span style="border: 1px dashed;
    padding: 0px;
    margin-left: 15%;    color: green;"><i class="fa fa-check" aria-hidden="true"></i> Thanks for your feedback</span></h2>
			@endif
        </div>
    </div>
</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>
	
	<script src="http://parsleyjs.org/dist/parsley.js"></script>
	<script>


  $('#formValoraciones').parsley()
  $( "#formValoraciones" ).submit(function( event ) {
	

});


	</script>
@endsection