import React, { useState, useRef, useEffect } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TouchableOpacity,
  _Text,
  ScrollView,
  ActivityIndicator,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import CheckBox from '@react-native-community/checkbox';
import ActionSheet, { ActionSheetRef } from 'react-native-actions-sheet';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Rating, AirbnbRating } from 'react-native-ratings';
import StarRating from 'react-native-star-rating';
import { useFocusEffect, useNavigation, useRoute } from '@react-navigation/native';
import { useContext } from 'react';
import { Context as BookingContext } from '../../context/BookingContext';
import { Context as AuthContext } from '../../context/AuthContext';
import { SliderShimmerBookingDetail } from '../../components/Skeleton';
import { InteractionManager } from 'react-native';
import { bookingDetail } from '../../network/Webconstant';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
// import Image from 'react-native-fast-image';
import { BackHandler } from 'react-native';
import moment from 'moment';

const BookingDetails = props => {
  const [mobileNumber, setMobileNumber] = useState('');
  const [conformPassword, SetPassword] = useState('');
  const [toggleCheckBox, setToggleCheckBox] = useState(false);
  const [loading, setLoading] = useState(false);
  const actionSheetRef = useRef(null);
  const [bookDetailData, setBookDetailData] = useState({})

  const [starCount, setStarCount] = useState(1);
  const onStarRatingPress = rating => {
    setStarCount(rating);
  };
  const navigation = useNavigation();

  const route = useRoute();
  const bookingID = route.params.bookID;
  // console.log('bid', bookingID);
  const {
    // bookingDetailsApi,
    ratingApi,
    state: {
      //bookDetailData,
      activityIndicator },
  } = useContext(BookingContext);
  const {
    state: { Token_ID },
  } = useContext(AuthContext);




  useFocusEffect(
    React.useCallback(() => {
      const task = InteractionManager.runAfterInteractions(async () => {
        // console.log('--------------booking details-------------');
        if (bookingID) {
          bookingDetailsApi();
        }
      });
      return () => task.cancel();
    }, [bookingID]),
  );

  const bookingDetailsApi = async () => {
    // console.log('-----------booking detail api ----------');
    setLoading(true)
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      axios({
        method: 'post',
        url: bookingDetail,
        data: { booking_id: bookingID },
        headers: { Authorization: `Bearer ${Localtoken}` }
      }).then(
        function (res) {
          if (res.data.status == true) {
            setBookDetailData(res.data.data)
            setLoading(false)
          } else {
            alert(res.data.message)
            setLoading(false)
          }
        }
      )
    } catch (error) {
      console.log('error is', error)
      setLoading(false)
    }
  }
  const starRatingApiHandler = () => {
    let data = { order_id: bookingID, star: starCount };
    let headers = { Authorization: `Bearer ${Token_ID}` };
    ratingApi(data, headers, () => {
      props.navigation.navigate('Logins');
    });
  };

  const handler = () => {
    props.navigation.goBack()
  }
  useFocusEffect(
    React.useCallback(() => {
      const backAction = () => {
        handler()
        return true;
      };

      const backHandler = BackHandler.addEventListener(
        'hardwareBackPress',
        backAction,
      );

      return () => backHandler.remove();
    }, [props]))
  console.log('===bookingid', bookDetailData.get_host_details)
  return (
    <>
      <View style={commonStyles.container}>
        <Header
          props={props}
          Heading={'#55ASD66'}
          onPress={() => props.navigation.navigate('MyBooking')}
        // {() => props.navigation.navigate('MyBooking')}
        />

        {
          loading ? (<SliderShimmerBookingDetail />) :
            <ScrollView contentContainerStyle={{}}>
              <View style={{}}>
                <View>
                  <Image source={{ uri: bookDetailData?.get_property?.image }} style={{ width: '100%', height: 200 }} />
                </View>
                <View
                  style={{
                    marginLeft: width * (20 / 375),
                    borderRadius: 10,
                    borderColor: '#E9E9E9',
                    borderWidth: 1,
                    marginTop: 10,
                    paddingVertical: 2,
                    width: width / 2,
                  }}>
                  <Text style={{ marginHorizontal: 10, width: width / 2, color: '#000' }}>
                    {bookDetailData?.booking_id}
                  </Text>
                </View>

                <View
                  style={{
                    marginHorizontal: width * (20 / 375),
                    marginTop: width * (10 / 375),
                  }}>
                  <View
                    style={{
                      alignItems: 'center',
                      flexDirection: 'row',
                      justifyContent: 'space-between',
                    }}>
                    <Text
                      style={{
                        fontSize: 17,
                        fontWeight: '500',
                        width: width / 2,
                        color: color.primaryColorBlack,
                      }}>
                      {bookDetailData?.get_property?.title}
                    </Text>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 10,
                      alignItems: 'center',
                    }}>
                    <Text
                      style={{
                        fontSize: 20,
                        fontWeight: 'bold',
                        color: color.primaryColorBlack,
                        marginRight: 8,
                      }}>
                      NGN {bookDetailData?.get_property?.price}.00
                    </Text>
                    <Text style={{ fontSize: 15, color: '#000' }}>night</Text>
                  </View>

                  <View
                    style={{
                      flexDirection: 'row',
                      alignItems: 'center',
                      marginTop: 14,
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <Image source={Images.Broze} />
                      <Text style={{ fontSize: 15, alignSelf: 'center', marginLeft: 5, color: '#000' }}>

                        Superhost
                      </Text>
                    </View>
                    <Text
                      style={{ fontSize: 8, marginHorizontal: 8, color: 'black' }}>
                      {'\u2B24'}
                    </Text>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <Image source={Images.StartIcon} />
                      <Text style={{ fontSize: 15, color: '#000' }}>
                        {bookDetailData?.get_property?.avg_rating}
                      </Text>
                    </View>
                    <Text
                      style={{ marginHorizontal: 8, fontSize: 8, color: 'black' }}>
                      {'\u2B24'}
                    </Text>
                    <View>
                      <Text style={{ fontSize: 15, color: '#000' }}>{bookDetailData?.get_property?.total_rating == undefined ? 0 : bookDetailData?.get_property?.total_rating} Review</Text>
                    </View>
                  </View>

                  <View
                    style={{
                      marginTop: 12,
                      flexDirection: 'row',
                      backgroundColor: color.appTextBackgoundColor,
                      alignContent: 'center',
                      borderRadius: 4,
                      borderColor: color.appOrangeColor,
                      borderWidth: 1,
                      alignItems: 'center',
                      height: 50,
                    }}>
                    {/* <TouchableOpacity>
                      <Image
                        source={Images.LocationIcons}
                        style={{
                          left: 10,
                          width: 20,
                          height: 20,
                          resizeMode: 'contain',
                        }}
                      />
                    </TouchableOpacity> */}

                    <Text style={{ left: 20, fontSize: 15, color: '#000' }}>
                      {bookDetailData?.get_property?.type}
                    </Text>
                  </View>
                </View>
              </View>
              <View style={{flexDirection:"row",justifyContent:'flex-start',alignItems:"center"}}>
              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  CHECK IN
                </Text>
                <Text style={{ color: color.primaryColorBlack }}>
                {moment(bookDetailData?.from_date).format('YYYY-MM-DD')}

                    </Text>
              </View>
              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  CHECK OUT
                </Text>
                <Text style={{ color: color.primaryColorBlack }}>
                     {moment(bookDetailData?.to_date).format('YYYY-MM-DD')}
                    </Text>
              </View>
              </View>
              <View style={{ borderWidth: 1, borderColor: '#e9e9e9',marginTop:10 }} />

              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  MANDATORY & INCLUDED SERVICES
                </Text>

                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                  <View style={{ flex: 0.7 }}>
                    <Text style={{ color: color.primaryColorBlack }}>
                      Final Cleaning
                    </Text>
                    <Text style={{ color: color.appTextColor }}>INCLUDED</Text>
                  </View>
                  <View
                    style={{
                      borderRightWidth: 2,
                      marginLeft: 40,
                      borderRightColor: '#e9e9e9',
                    }}
                  />
                  <View style={{ marginLeft: width * (40 / 375) }}>
                    <Text style={{ color: color.primaryColorBlack }}>
                      Internet Access
                    </Text>
                    <Text style={{ color: color.appTextColor }}>INCLUDED</Text>
                  </View>
                </View>

                <Text
                  style={{
                    fontWeight: '500',
                    marginTop: 10,
                    color: color.primaryColorBlack,
                  }}>
                  Security deposit (Refundable)
                </Text>
                <Text style={{ color: color.appTextColor, marginBottom: 20 }}>
                  NGN{bookDetailData?.get_property?.security_deposit_amount}
                  /Booking
                </Text>
              </View>
              <View style={{ borderWidth: 1, borderColor: '#e9e9e9' }} />
              <View style={{ marginHorizontal: 20, marginTop: 15 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  SECURITY DEPOSIT
                </Text>
                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                  <Text
                    style={{ fontWeight: '500', color: color.primaryColorBlack }}>
                    Amount:
                  </Text>
                  <Text style={{ color: color.appTextColor }}>
                    NGN{bookDetailData?.get_property?.security_deposit_amount}
                    /booking
                  </Text>
                </View>
                <Text
                  style={{
                    fontWeight: '500',
                    marginTop: 10,
                    color: color.primaryColorBlack,
                  }}>
                  Payment method:
                </Text>
                <Text style={{ color: color.appTextColor, marginBottom: 10 }}>
                  Both options are available (Card of Bank Transfer) To be paid
                  when booking or checkout.
                </Text>
              </View>
              <View
                style={{
                  borderWidth: 1,
                  borderColor: '#e9e9e9',
                  marginVertical: 5,
                }}
              />
              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  ADDITIONAL NOTES
                </Text>
                <Text
                  style={{
                    fontWeight: '500',
                    marginTop: 10,
                    color: color.primaryColorBlack,
                  }}>
                  Check-in schedule
                </Text>
                <Text style={{ color: color.appTextColor, marginBottom: 6 }}>
                  from 15:00 to 18:00 every day
                </Text>

                <View style={{ flexDirection: 'row', marginBottom: 10 }}>
                  <Text
                    style={{ fontWeight: '500', color: color.primaryColorBlack }}>
                    Check-out schedule:
                  </Text>
                  <Text style={{ color: color.appTextColor }}> Before 12:00</Text>
                </View>
                <Text style={{ color: color.appTextColor, marginBottom: 10 }}>
                  Refund of security deposit to the credit card 24/48h after your
                  depature.
                </Text>
              </View>
              <View
                style={{
                  borderWidth: 1,
                  borderColor: '#e9e9e9',
                  marginVertical: 5,
                }}
              />
              {bookDetailData?.get_property?.get_extra_service[0]?.get_service_data?.name == null ? null :
                <>
                  <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                    <Text
                      style={{
                        fontWeight: '600',
                        fontSize: 15,
                        color: color.primaryColorBlack,
                      }}>
                      OPTIONAL SERVICES
                    </Text>

                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.get_property?.get_extra_service[0]?.get_service_data?.name}
                    </Text>
                    {/* <Text style={{ color: color.appTextColor, marginBottom: 10 }}>
                  (NGN 10,000.00/booking).
                </Text> */}
                  </View>
                  <View
                    style={{
                      borderWidth: 1,
                      borderColor: '#e9e9e9',
                      marginVertical: 5,
                    }}
                  />
                </>
              }
              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  YOUR PERSONAL DATA
                </Text>
                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                  <View style={{ flex: 0.58 }}>
                    <Text style={{ color: color.primaryColorBlack }}>Name</Text>
                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.personal_first_name}{' '}
                      {bookDetailData.personal_last_name}
                    </Text>
                  </View>
                  {/* <View style={{ borderRightWidth: 1, borderRightColor: '#e9e9e9' }} /> */}
                  {/* <View style={{ marginHorizontal:50 }}>
                                    <Text style={{ color: color.primaryColorBlack }}>Age</Text>
                                    <Text style={{ color: color.appTextColor }}>25 Year</Text>

                                </View> */}
                </View>
                <View
                  style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10, justifyContent: 'flex-start' }}>
                  <View style={{ flex: 0 }}>
                    <Text style={{ color: color.primaryColorBlack }}>
                      Phone Number
                    </Text>
                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.personal_phone_number}
                    </Text>
                  </View>
                  <View style={{ borderRightWidth: 1, borderColor: '#e9e9e9', marginHorizontal: 20 }} />
                  <View style={{ marginHorizontal: 50 }}>
                    <Text style={{ color: color.primaryColorBlack }}>E-mail</Text>
                    <Text style={{ color: color.appTextColor, width: width / 2 }}>
                      {bookDetailData?.personal_email}
                    </Text>
                  </View>
                </View>
              </View>
              <View
                style={{
                  borderWidth: 1,
                  borderColor: '#e9e9e9',
                  marginVertical: 5,
                }}
              />
              <View style={{ marginHorizontal: 20, marginTop: 10 }}>
                <Text
                  style={{
                    fontWeight: '600',
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  YOUR GUEST DATA
                </Text>
                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                  <View style={{ flex: 0.58 }}>
                    <Text style={{ color: color.primaryColorBlack }}>Name</Text>
                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.guest_first_name} {bookDetailData?.guest_last_name}
                    </Text>
                  </View>
                  {/* <View style={{ borderRightWidth: 1, borderColor: '#e9e9e9' }} />
                                <View style={{ marginHorizontal:50 }}>
                                    <Text style={{ color: color.primaryColorBlack }}>Age</Text>
                                    <Text style={{ color: color.appTextColor }}>25 Year</Text>

                                </View> */}
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    marginTop: 10,
                    marginBottom: 10,
                    justifyContent: 'flex-start',
                  }}>
                  <View style={{}}>
                    <Text style={{ color: color.primaryColorBlack }}>
                      Phone Number
                    </Text>
                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.guest_phone_number}
                    </Text>
                  </View>
                  <View
                    style={{
                      borderRightWidth: 1,
                      borderColor: '#e9e9e9',
                      marginHorizontal: 20,
                    }}
                  />
                  <View style={{ marginHorizontal: 50 }}>
                    <Text style={{ color: color.primaryColorBlack }}>E-mail</Text>
                    <Text style={{ color: color.appTextColor }}>
                      {bookDetailData?.guest_email}
                    </Text>
                  </View>
                </View>
              </View>
              <View
                style={{
                  borderWidth: 1,
                  borderColor: '#e9e9e9',
                  marginVertical: 5,
                }}
              />
              <View
                style={{ width: width, height: height / 4, paddingHorizontal: 4 }}>
                <Text
                  style={{
                    paddingLeft: 15,
                    paddingRight: 15,
                    marginTop: 10,
                    fontSize: 15,
                    color: color.primaryColorBlack,
                  }}>
                  PRICE DETAIL
                </Text>
                <View
                  style={{
                    marginTop: 5,
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    paddingLeft: 15,
                    paddingRight: 15,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.appTextColor,
                    }}>
                    NGN {bookDetailData?.per_night_price}.00 X
                    <Text> {bookDetailData?.total_days} </Text>nights
                  </Text>
                  <Text
                    style={{
                      fontSize: 15,
                      fontWeight: '500',
                      color: color.primaryColorBlack,
                    }}>
                    NGN{' '}
                    {bookDetailData?.per_night_price * bookDetailData?.total_days}
                    .00
                  </Text>
                </View>

                <View
                  style={{
                    marginTop: 5,
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    paddingLeft: 15,
                    paddingRight: 15,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.appTextColor,
                    }}>
                    Service fee
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookDetailData?.security_deposite}.00
                  </Text>
                </View>
                <View
                  style={{
                    marginTop: 5,
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    paddingLeft: 15,
                    paddingRight: 15,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.appTextColor,
                    }}>
                    Discount
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookDetailData?.discount_amount}.00
                  </Text>
                </View>
                <View
                  style={{
                    marginTop: 5,
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    paddingLeft: 15,
                    paddingRight: 15,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.appTextColor,
                    }}>
                    Extra Amount
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookDetailData?.optional_service_amount}.00
                  </Text>
                </View>

                <View
                  style={{
                    marginTop: 5,
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    paddingLeft: 15,
                    paddingRight: 15,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.appTextColor,
                    }}>
                    Total
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 15,
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookDetailData?.total_amount}.00
                  </Text>
                </View>
                {/* <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    marginVertical: 15,
                    width: width - 30,
                    alignSelf: 'center',
                  }}>
                  <TouchableOpacity
                    style={{
                      width: 160,
                      borderRadius: width * (8 / 375),
                      borderWidth: 2,
                      borderColor: color.appOrangeColor,
                      backgroundColor: color.appLightOrangeColor,
                    }}>
                    <Text
                      style={{
                        marginHorizontal: width * (40 / 375),
                        marginVertical: 10,
                        color: color.appOrangeColor,
                        alignSelf: 'center',
                      }}>
                      Re-Book
                    </Text>
                  </TouchableOpacity>
                  <TouchableOpacity
                    onPress={() => actionSheetRef.current?.show()}>
                    <View
                      style={{
                        width: 160,
                        borderRadius: width * (8 / 375),
                        borderWidth: 2,
                        borderColor: color.appOrangeColor,
                        backgroundColor: color.appLightOrangeColor,
                      }}>
                      <Text
                        style={{
                          marginHorizontal: width * (40 / 375),
                          marginVertical: 10,
                          color: color.appOrangeColor,
                          alignSelf: 'center',
                        }}>
                        Rate Now
                      </Text>
                    </View>
                  </TouchableOpacity>
                </View> */}

                <View style={{ marginBottom: 50 }} />
                <ActionSheet ref={actionSheetRef}>
                  <SafeAreaView>
                    <View style={{ marginTop: 8 }}>
                      <View
                        style={{
                          borderBottomColor: color.appTextBackgoundColor,
                          borderBottomWidth: 1,
                          marginBottom: 20,
                        }}>
                        <View style={{ paddingVertical: 5 }}>
                          <Text
                            style={{
                              marginHorizontal: 15,
                              fontSize: 20,
                              fontWeight: '500',
                              marginBottom: 10,
                              color: color.primaryColorBlack,
                            }}>
                            Rate Now
                          </Text>
                        </View>
                        <View
                          style={{ borderWidth: 0.8, borderColor: '#e9e9e9' }}
                        />
                        <View
                          style={{
                            marginTop: 25,
                            marginBottom: 20,
                            justifyContent: 'center',
                          }}>
                          <StarRating
                            disabled={false}
                            emptyStar={Images.emptyStarImage}
                            fullStar={Images.StartBigIcon}
                            // halfStar={'ios-star-half'}
                            // iconSet={Images.selectedIcons}
                            emptyStarColor={'#C1C1C1'}
                            // fullStarColor={'red'}
                            maxStars={5}
                            rating={starCount}
                            selectedStar={rating => onStarRatingPress(rating)}
                            starStyle={{ marginHorizontal: 12 }}
                            starSize={40}
                            containerStyle={{
                              marginHorizontal: 20,
                              alignSelf: 'flex-start',
                              margin: 8,
                              marginBottom: 14,
                            }}
                          />
                          {/* <Rating
                                                type='star'
                                                ratingCount={5}
                                                imageSize={60}
                                                style={{margin:12}}

                                            //     showRating
                                            //     // onFinishRating={this.ratingCompleted}
                                            /> */}
                          {/* <Image resizeMode="contain" style={{ height: 35, width: 35, marginRight: width * (20 / 375) }} source={Images.StartIcon} />
                                        <Image resizeMode="contain" style={{ height: 35, width: 35, marginRight: width * (20 / 375) }} source={Images.StartIcon} />
                                        <Image resizeMode="contain" style={{ height: 35, width: 35, marginRight: width * (20 / 375) }} source={Images.StartIcon} />
                                        <Image resizeMode="contain" style={{ height: 35, width: 35, marginRight: width * (20 / 375) }} source={Images.StartIcon} />
                                        <Image resizeMode="contain" style={{ height: 35, width: 35, marginRight: width * (20 / 375) }} source={Images.StartIcon} /> */}
                        </View>

                        <TouchableOpacity
                          onPress={
                            () => starRatingApiHandler()
                            //  props.navigation.navigate('Logins')
                          }
                          style={{
                            marginTop: 20,
                            backgroundColor: color.appBlueColor,
                            width: width - 30,
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            alignSelf: 'center',
                            height: 50,
                          }}>
                          <View
                            style={{
                              position: 'absolute',
                              flex: 1,
                            }}>
                            <Image
                              source={Images.whiteDot}
                              style={{
                                paddingLeft: 70,
                                width: 25,
                                height: 25,
                                resizeMode: 'contain',
                              }}
                            />
                          </View>

                          <Text
                            style={{
                              textAlign: 'center',
                              fontSize: 15,
                              color: color.appWhiteColor,
                            }}>
                            Save
                          </Text>
                        </TouchableOpacity>
                      </View>
                    </View>
                  </SafeAreaView>
                </ActionSheet>
              </View>
              <View style={{ marginBottom: 40 }} />
            </ScrollView>
        }
      </View>
    </>
  );
};

export default BookingDetails;
