import createDataContext from './createDataContext';

// import { navigate } from "../navigationRef";
// import * as Facebook from "expo-facebook";
// import firebase from "firebase";
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {
  add_To_Favourite,
  Booking,
  bookingDetail,
  cancelBooking,
  checkCouponCode,
  Forgot_Password,
  getAllCountry,
  getAllProvince,
  getBooking,
  getOffer,
  getOfferDetailApiUse,
  getAllCity,
  Home_Details,
  HOME_URL,
  Login_Url,
  Logout,
  my_favorites,
  Send_Otp,
  Sign_UP,
  starRating,
  Verify_Otp,
  getAllArea,
  AddProvince,
  AddCity,
  AddArea,
} from '../network/Webconstant';
import { showMessage } from 'react-native-flash-message';
import { useRoute } from '@react-navigation/native';
import { Toast } from 'react-native-toast-message/lib/src/Toast';

const bookingReducer = (state, action) => {
  switch (action.type) {
    case 'updateBookingData':
      let country = state.bookingData;
      country['country'] = action.payload;
      return {
        ...state,
        bookingData: country,
        activityIndicator: false,
      };
    // countryCode['country'] = action.payload
    // return { ...state, countryData: country }
    case 'updateCountryCodeData':
      let code = state.countryData;
      code['countrycode'] = action.payload;
      return { ...state, provinceData: code };
    case 'updateProvince':
      let province = state.provinceData;
      province['country'] = action.payload;
      return { ...state, provinceData: province };
    case 'updateCity':
      let city = state.cityData;
      city['country'] = action.payload;
      return { ...state, city: city };
    case 'updateArea':
      let area = state.areaData;
      area['country'] = action.payload;
      return { ...state, city: area };
    case 'updateHomeDetails':
      return { ...state, offerData: action.payload, activityIndicator: false };
    case 'updateOfferDetails':
      return { ...state, offerDetails: action.payload };
    case 'updateApplyCoupon':
      return { ...state, applyCoupon: action.payload };
    case 'updateMyBooking':
      return { ...state, myBooking: action.payload };
    case 'updateBookingDetailsData':
      return {
        ...state,
        bookDetailData: action.payload,
        activityIndicator: false,
      };
    case 'appendBookingData':
      let temp = state.bookingData;
      temp[action.payload.type] = action.payload.data;
      return { ...state, bookingData: temp };
    case 'loadActivityIndicator':
      return { ...state, activityIndicator: !state.activityIndicator };
    case 'AUTH_FAILED':
      return {
        ...state,
        errorMessage: action.payload,
        activityIndicator: false,
      };
    case 'SIGN_OUT_USER':
      return { ...state, user_token: '', activityIndicator: false };
    default:
      return state;
  }
};

const mobileBooking =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      // property_id:1
      // start_date:2022-12-15
      // end_date:2022-12-20
      // total_booking_amount:100
      // adultCount:2
      // childCount:1
      // infantCount:0
      // petCount:0
      // total_days:5
      // discount_code:FLAT15%
      // discount_id:1
      // discount_amount:100
      // is_agency:No
      // payment_method:bank_account
      // book_for:self
      // policy_read:Yes
      // first_name:Mayank
      // last_name:Agrawal
      // address:Jaipur, Rajasthan, India
      // country_id:101
      // city:Jaipur
      // postal_code:302020
      // phone_number:7894567894
      // email:test11@mailinator.com
      // comment:
      // guest_first_name:Guest first
      // guest_last_name:Guest last
      // guest_address:Jaipur, Rajasthan, India
      // guest_city:Jaipur
      // guest_zipcode:302020
      // guest_country_id:101
      // guest_phone_number:4561234561
      // guest_email:guest@gmail.com
      // card_holder_name:
      // card_number:
      // month:
      // year:
      // cvv:
      // selected_options:[{"id":"3","name":"Photoshoot","value":"100000"},{"id":"4","name":"Early Check in","value":"5000"},{"id":"7","name":"Game night","value":"10000"}]
      try {
        axios({
          method: 'post',
          url: Booking,
          data,
          headers,
        })
          .then(function (response) {
            if (response.data.status) {
              alert(response.data.message);
              callback();
            } else {
              alert(response.data.message);
            }
            // let temp = response?.data;
            // console.log('====mobileBookingResponse', temp.data)
            // if (temp.status === true) {
            //   callback()
            // }
          })
          .catch(e => {
            console.log('BookingContext:', e);
          });
      } catch (error) {
        console.log('BookingContext:', error);
      }
    };

const getAllCountryApi = dispatch => async data => {
  try {
    dispatch({ type: 'loadActivityIndicator' });

    axios({
      method: 'get',
      url: getAllCountry,
      data
    })
      .then(function (response) {
        if (response.data.status === true) {
          // console.log('====res country', response.data.data);
          let temp = response.data.data.map(item => {
            return { value: item.id, label:"("+ "+"+item.phonecode+")" + item.name  };
          });
          dispatch({ type: 'updateBookingData', payload: temp });
        }
      })
      .catch(error => console.log('something went wrong ', error));
    dispatch({ type: 'loadActivityIndicator' });
  } catch (error) {
    dispatch({ type: 'loadActivityIndicator' });
    console.log('eerrr', error);
  }
};

const getAllCountryCodeApi = dispatch => async data => {
  try {
    axios({
      method: 'get',
      url: getAllCountry,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res country', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.phonecode };
        });
        dispatch({ type: 'updateCountryCodeData', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};

const getProvinceApi = dispatch => async data => {
  try {
    axios({
      method: 'post',
      url: getAllProvince,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res province', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateProvince', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};
const getProvinceApiModal = dispatch => async data => {
  try {
    axios({
      method: 'post',
      url: AddProvince,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res province modal', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateProvince', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};

const getCityApiModal = dispatch => async data => {
  try {
    axios({
      method: 'post',
      url: AddCity,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res city', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateCity', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};
const getCityApi = dispatch => async data => {
  console.log('----dadta', data);
  try {
    // let body = {
    //   province_id: data
    // }
    axios({
      method: 'post',
      url: getAllCity,
      data: data
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res city', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateCity', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};

const getAreaApiModal = dispatch => async data => {
  try {
    axios({
      method: 'post',
      url: AddArea,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res city', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateArea', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};
const getAreaApi = dispatch => async data => {
  try {
    axios({
      method: 'post',
      url: getAllArea,
      data,
    }).then(function (response) {
      if (response.data.status === true) {
        // console.log('====res city', response.data.data);
        let temp = response.data.data.map(item => {
          return { value: item.id, label: item.name };
        });
        dispatch({ type: 'updateArea', payload: temp });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};

const getOfferApi = dispatch => async headers => {
  try {
    axios({
      method: 'get',
      url: getOffer,
      headers,
    }).then(function (response) {
      // console.log('====res get offer', response.data.status);

      if (response.data.status === true) {
        dispatch({ type: 'updateHomeDetails', payload: response.data.data });
      }
    });
  } catch (error) {
    console.log('eerrr', error);
  }
};

const getOfferDetailApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'post',
          url: getOfferDetailApiUse,
          data,
          headers,
        })
          .then(function (res) {
            // console.log('=====res', res.data.data);
            if (res.data.status == true) {
              dispatch({ type: 'updateOfferDetails', payload: res.data.data });
              callback();
            } else {
              // alert(res.data.message)
              dispatch({ type: 'loadActivityIndicator' });
            }
          })
          .catch(e => {
            console.log('error', e);
            dispatch({ type: 'loadActivityIndicator' });
          });
      } catch (error) {
        console.log('e', error);
        dispatch({ type: 'loadActivityIndicator' });
      }
    };
const getCouponCodeApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        axios({
          method: 'post',
          url: checkCouponCode,
          data,
          headers,
        })
          .then(function (res) {
            // console.log('=====res', res.data);
            if (res.data.status == true) {
              alert(res.data.message);
              dispatch({ type: 'updateApplyCoupon', payload: res.data.data });
              callback();
            }
          })
          .catch(e => {
            console.log('error', e);
          });
      } catch (error) {
        console.log('e', error);
      }
    };

const getMyBookingApi =
  dispatch =>
    async (headers, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'get',
          url: getBooking,
          headers,
        })
          .then(function (response) {
            if (response.data.status == true) {
              dispatch({ type: 'updateMyBooking', payload: response.data.data });
              // console.log('response booking', response)
              // alert(response.data.message)
              // dispatch({ type: "loadActivityIndicator" })
            } else {
              // alert(response.data.message)
              dispatch({ type: 'loadActivityIndicator' });
            }
          })
          .catch(e => {
            console.log('e', e);
            dispatch({ type: 'loadActivityIndicator' });
          });
      } catch (error) {
        console.log('error', error);
        dispatch({ type: 'loadActivityIndicator' });
      }
    };

const cancelBookingApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        await axios({
          method: 'post',
          url: cancelBooking,
          data,
          headers,
        }).then(function (res) {
          if (res.data.status == true) {
            // console.log('===cancel boking', res);
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message,
            });
            callback();
          } else {
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message,
            });
          }
        });
      } catch (error) {
        console.log('===cancel', error);
      }
    };

const bookingDetailsApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'post',
          url: bookingDetail,
          data,
          headers,
        })
          .then(function (res) {
            // console.log('======bookingData', res.data)
            if (res.data.status == true) {
              dispatch({
                type: 'updateBookingDetailsData',
                payload: res.data.data,
              });
              dispatch({ type: 'loadActivityIndicator' });
              // alert(res.data.message)
            } else {
              alert(res.data.message);
              dispatch({ type: 'loadActivityIndicator' });
            }
          })
          .catch(e => {
            console.log('ee', e);
            dispatch({ type: 'loadActivityIndicator' });
          });
      } catch (error) {
        console.log('error is', error);
        dispatch({ type: 'loadActivityIndicator' });
      }
    };

const ratingApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        axios({
          method: 'post',
          url: starRating,
          data,
          headers,
        }).then(function (res) {
          if (res.data.status == true) {
            console.log('star', res);
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message
            })

            callback();
          } else {
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: res.data.message
            })
          }
        });
      } catch (error) { }
    };

const updateBookingData =
  dispatch =>
    (data, callback = () => { }) => {
      try {
        dispatch({
          type: 'appendBookingData',
          payload: { type: data.type, data: data.data },
        });
        callback();
      } catch (e) {
        console.log('updateBookingData:', e);
      }
    };

export const { Provider, Context } = createDataContext(
  bookingReducer,
  {
    mobileBooking,
    getAllCountryApi,
    getOfferApi,
    updateBookingData,
    getCouponCodeApi,
    getOfferDetailApi,
    getMyBookingApi,
    cancelBookingApi,
    bookingDetailsApi,
    ratingApi,
    getProvinceApi,
    getAllCountryCodeApi,
    getCityApi,
    getAreaApi,
    getProvinceApiModal,
    getAreaApiModal,
    getCityApiModal
  },
  {
    USER_ID: null,
    errorMessage: '',
    activityIndicator: true,
    bookingData: {},
    provinceData: {},
    cityData: {},
    areaData: {},
    countryData: {},
    offerData: [],
    offerDetails: {},
    applyCoupon: {},
    myBooking: [],
    bookDetailData: {},
  },
);
