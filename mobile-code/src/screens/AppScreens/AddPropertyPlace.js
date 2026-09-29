import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  _Text,
  ScrollView,
  FlatList,
  ActivityIndicator,
  Modal,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import LinearGradient from 'react-native-linear-gradient';
import axios from 'axios';
// import { useRoute } from "@react-navigation/native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import {
  getAllServices,
  becomeahost_add_property,
  MAIN_URL,
} from '../../network/Webconstant';
import ActionSheet from 'react-native-actions-sheet';
import { Linking } from 'react-native';
import AxiosCurlirize from 'axios-curlirize';
import { Title } from 'react-native-paper';
import { BackHandler } from 'react-native';

AxiosCurlirize(axios);

const AddPropertyPlace = props => {
  const route = useRoute();

  const [select, setSelect] = useState(false);
  const [propData, setPropData] = useState([]);
  //   const [image, setImage] = useState('');
  const [user, setUser] = useState('');
  const [selectedArray, setSelectedArray] = useState([]);
  const [loader, setLoader] = useState(false)
  const [modalVisible, setModalVisible] = useState(false)
  const [imgArr,setImgArr] = useState([])
  // const [newData, setData] = useState(route.params.categoryId);

  const actionSheetRef = useRef(null);

  useEffect(() => {
  setImgArr(route?.params?.image)
    getUser();

    axios({
      method: 'get',
      url: getAllServices,
      // headers: { "content-type": "application/x-www-form-urlencoded" }
    })
      .then(response => {
        if (response?.data?.status == true) {
          setPropData(response.data.data);
        } else {
          console.log("api error", response);
        }
      })
      .catch(e => { console.log('error place',e);});
  }, []);

  const getUser = async () => {
    let userID = await AsyncStorage.getItem('USER');
    console.log('user id', userID);
    setUser(userID);
  };

  

  const addToHandler = () => {
    if (selectedArray == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select at least one category',
      });
    } else {
      addPropertyApi();
console.log('----------------=-=-=-i',imgArr,imgArr.length)
    }
  };

  const handler = () => {
    props.navigation.goBack()
  }
  useEffect(() => {
    const backAction = () => {
      handler()
      return true;
    };

    const backHandler = BackHandler.addEventListener(
      'hardwareBackPress',
      backAction,
    );

    return () => backHandler.remove();
  }, [props])
  const addPropertyApi = async () => {
    // console.log('===image', image[0]?.uri);
    setModalVisible(true)

    var data = new FormData();
    data.append('user_id', user);
    data.append('type', route?.params?.propertyId);
    data.append('category', route?.params?.categoryId?.map(id => id).join(','));
    data.append('title', route?.params?.title);
    data.append('description', route?.params?.description);
    data.append('price', route?.params?.price);
    data.append('address', route?.params?.address);
    data.append('latitude', route?.params?.latitude);
    data.append('longitude', route?.params?.longitude);
    data.append('country_id', route?.params?.countryId);
    data.append('province_id', route?.params?.provinceId);
    data.append('city_id', route?.params?.cityId);
    data.append('area', route?.params?.areaId);
    data.append('postal_code', route?.params?.postalCode);
    data.append('street_name', route?.params?.streetName);
    data.append('street_type', route?.params?.streetType);
    data.append('street_number', '');
    data.append('house_number', '');
    data.append('floor', '');
    data.append('staircase', '');
    data.append('apartment_door_no', '');
    data.append('bedrooms', route?.params?.bedrooms);
    data.append('bathrooms', route?.params?.bathrooms);
    data.append('kitchens', route?.params?.kitchen);
    data.append('beds', route?.params?.beds);
    // data.append('amenity', route?.params?.amenityId?.map(id => id).join(','));
    data.append('standout_amenities', '');
    data.append('extra_services', ''); 
    route?.params?.image.forEach(element => {
      data.append('image[]', element) 
    });
    // data.append('image[]', route?.params?.image) 
    data.append('max_guest', route?.params?.maxGuest);
    data.append('pets_allow', route.params.petsAllow);
    data.append('minimum_no_of_nights', route?.params?.numberOfNights);
    data.append('cctv', route.params.cctv);
    data.append('cctv_locations', route.params.cameraLocation);
    data.append('wifi_username', route.params.wifiUserName);
    data.append('wifi_password', route.params.wifiPassword);
    data.append(
      'no_of_television',
      route.params.television ? route.params.television : 0,
    );
    data.append('location_of_television', route.params.televisionLocation);
    data.append('response_time', route.params.responseTime);
    data.append('party_rate_commission', route?.params?.partyCommission);
    data.append('allow_a_day_booking', route.params.allowDayBooking);
    data.append('house_rule', route.params.houseRules);
    data.append('elevator', '1');
    // console.log(data)
    // setModalVisible(false)
    axios({
      method: 'post',
      url: becomeahost_add_property,

      headers: {
        "Accept": 'multipart/form-data',
        'Content-Type': 'multipart/form-data',
      },
      data: data,
    })
      .then(response => {
        // alert(response?.data?.status)
        console.log("api response ", response.data)
        if (response?.data?.status === true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setModalVisible(false)
          props.navigation.navigate('ThankYou')
          // actionSheetRef?.current?.show();
        } else {
          // console.log("false ==== ", data, response.data.message)
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message || response?.data?.city_id || response?.data?.country_id || response?.data?.province_id || response.data.address,
          });
          setModalVisible(false)
          // props.navigation.navigate("BecomeaHostHome")
        }
      })
      .catch(e => {
        console.log('error is  ===', e, data);
        setModalVisible(false)
      });
  };

  
  const actionSelection = (item, states) => {
    let arr = [...selectedArray];
    if (states) {
      const index = arr.indexOf(item?.id);
      if (index > -1) {
        // only splice array when item is found
        arr.splice(index, 1); // 2nd parameter means remove one item only
      }
    } else {
      arr.push(item?.id);
    }
    setSelectedArray(arr);
  };

  const renderItem = ({ item, index }) => {
    const states = selectedArray.includes(item?.id);
    return (
      <TouchableOpacity
        onPress={() => actionSelection(item, states)}
        style={[
          styles.flatView,
          { borderColor: states ? color.appYellowColor : color.lightGray },
          //  { width: item.width }
        ]}>
        <Image
          source={{ uri: item.image }}
          style={{
            height: 50,
            width: 50,
            resizeMode: 'cover',
            borderTopRightRadius: 5,
            borderBottomRightRadius: 5,
            alignSelf: 'center',
            marginTop: 5,
          }}
        />
        <Text
          style={{
            alignSelf: 'center',
            marginVertical: 10,
            fontSize: 15,
            fontWeight: '500',
            marginBottom: 14,
            paddingHorizontal: 10, color: '#000'
          }}>
          {item.name}
        </Text>
      </TouchableOpacity>
    );
  };
  return (
    <View style={commonStyles.container}>
      <Header
        props={props}
        Heading={'Add Property'}
        onPress={() => props.navigation.goBack()}
      />
      <ScrollView>
        <Text
          style={{
            marginTop: 20,
            fontSize: 20,
            fontWeight: '600',
            marginHorizontal: 20,
            color: color.primaryColorBlack,
          }}>
          Do you have any of these at your place?
        </Text>
        <View
          style={{
            flexDirection: 'row',
            justifyContent: 'center',
            marginVertical: 10,
            marginHorizontal: 14,
          }}>
          <FlatList
            data={propData}
            renderItem={renderItem}
            numColumns={2}
            keyExtractor={(item, index) => index.toString()}
          />
         
        </View>

       
        <View style={{ marginHorizontal: 20 }}>
          <Text
            style={{
              fontWeight: '600',
              fontSize: 20,
              alignSelf: 'center',
              color: color.primaryColorBlack,
            }}>
            Some Important things to know
          </Text>
          <View
            style={{
              flexDirection: 'row',
              justifyContent: 'center',
              alignItems: 'center',
              alignSelf: 'center',
            }}>
            <TouchableOpacity
              onPress={() =>
                Linking.openURL(
                  `${MAIN_URL}/privacy-policy`,
                )
              }>
              <Text style={{ fontSize: 13, fontWeight: '500', color: '#000' }}>
                Privacy policy{' '}
              </Text>
            </TouchableOpacity>
            <Image source={Images.SmallOrangeDotIcon} />
            <TouchableOpacity
              onPress={() =>
                Linking.openURL(
                  `${MAIN_URL}/help-center`,
                )
              }>
              <Text style={{ fontSize: 13, fontWeight: '500', color: '#000' }}>

                Help Center
              </Text>
            </TouchableOpacity>
            <Image source={Images.SmallOrangeDotIcon} />
            <TouchableOpacity
              onPress={() =>
                Linking.openURL(
                  `${MAIN_URL}/cancellation-policy`,
                )
              }>
              <Text style={{ fontWeight: '500', fontSize: 13, color: '#000' }}>
                {' '}
                Cancellation Policy
              </Text>
            </TouchableOpacity>
          </View>
        </View>

        <View style={{ flex: 1, justifyContent: 'flex-end' }}>
          <View
            style={{
              padding: 20,
              backgroundColor: color.appTextBackgoundColor,
              flexDirection: 'row',
              justifyContent: 'space-between',
            }}>
            <TouchableOpacity
              onPress={() => props.navigation.goBack()}
              style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}>
              <Text
                style={{
                  fontSize: 16,
                  color: color.appWhiteColor,
                  marginHorizontal: 40,
                  marginVertical: 20,
                  fontWeight: '600',
                }}>
                Back
              </Text>
            </TouchableOpacity>
            <View>
              <TouchableOpacity onPress={() =>

                addToHandler()
              }>
                <LinearGradient
                  colors={[color.appYellowColor, color.appOrangeColor]}
                  style={(styles.linearGradient, { borderRadius: 10 })}>
                  <Text
                    style={{
                      marginHorizontal: 40,
                      marginVertical: 20,
                      color: color.appWhiteColor,
                      fontWeight: '600',
                      fontSize: 18,
                    }}>
                    Next
                  </Text>

                </LinearGradient>

              </TouchableOpacity>

            </View>
          </View>
        </View>
      </ScrollView>
      <Modal
        // animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          // Alert.alert('Modal has been closed.');
          setModalVisible(!modalVisible);
        }}>
        <View
          style={{
            flex: 0.1,
            justifyContent: 'center',
            alignItems: 'center',
            marginTop: '70%',
            backgroundColor: 'rgba(0, 0, 0,0.6)',
            marginHorizontal: '40%',
          }}>
          <ActivityIndicator size="large" color="#F99428" />
        </View>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  input: {
    height: 40,
    margin: 12,
    padding: 20,
    backgroundColor: color.appTextBackgoundColor,
    borderRadius: 4,
  },
  container: {
    marginRight: 10,
    fontSize: 20,
    color: color.appWhiteColor,
    fontWeight: '600',
  },
  AddingBackground: {
    height: 30,
    width: 30,
    borderRadius: 15,
    backgroundColor: color.appTextBackgoundColor,
    alignItems: 'center',
    justifyContent: 'center',
  },

  linearGradient: {
    flex: 1,
    paddingLeft: 15,
    paddingRight: 15,
  },
  buttonText: {
    fontSize: 18,
    fontFamily: 'Gill Sans',
    textAlign: 'center',
    marginVertical: 20,
    color: '#ffffff',
    marginHorizontal: 50,

    justifyContent: 'center',
    alignSelf: 'center',
    backgroundColor: 'transparent',
  },
  flatView: {
    backgroundColor: '#F5F7FA',
    width: width / 2 - 30,
    borderRadius: 10,
    marginVertical: 10,
    borderWidth: 1,
    marginHorizontal: 8,
  },
});

export default AddPropertyPlace;
