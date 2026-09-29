import React, { useState, useEffect } from 'react';
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
  Modal,
  Platform,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import LinearGradient from 'react-native-linear-gradient';
import MapView from 'react-native-maps';
import { launchCamera, launchImageLibrary } from 'react-native-image-picker';
import { useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { BackHandler } from 'react-native';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';

let imgArray = [];
const AddPropertyPhotos = props => {
  const route = useRoute();

  const [modalVisible, setModalVisible] = useState(false);
  const [imageUri, setImageUri] = useState([]);
  const [filePathArray, setFilePathArray] = useState([]);
  let arr = [];



  const CameraAction = () => {
    let options = {
      storageOption: {
        path: 'images',
        mediaType: 'photo',
      },
      includeBase64: true,
      selectionLimit: 5,
    };
    launchCamera(options, response => {
      //   console.log('Response =', response);
      if (response.didCancel) {
        // console.log('User cancelled image picker');
      } else if (response.error) {
        // console.log('ImagePicker Error:', response.error);
      } else if (response.CustomButton) {
        // console.log('User tapped custom button:', response.CustomButton);
      } else {
        const source = {
          uri: 'data:image/jpeg;base64,' + response?.assets[0].base64,
        };
        // onClose(source)
        // setImageUri(response?.assets);
        setImageUri(prev => [...prev, ...response.assets]);
        setModalVisible(false);
      }
    });
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
  const GalleryAction = () => {
    let options = {
      storageOption: {
        path: 'images',
        mediaType: 'photo',
      },
      includeBase64: true,
      selectionLimit: 5,
    };
    launchImageLibrary(options, response => {
      //   console.log('Response =', response);
      if (response.didCancel) {
        // console.log('User cancelled image picker');
      } else if (response.error) {
        // console.log('ImagePicker Error:', response.error);
      } else if (response.CustomButton) {
        // console.log('User tapped custom button:', response.CustomButton);
      } else {
        const source = {
          uri: 'data:image/jpeg;base64,' + response?.assets[0]?.base64,
        };

        //  let arr=[...imageUri]

        //  arr.push(response.assets)
        //  setImageUri([...arr])
        // console.log(imageUri.length,response.assets.length,'-------')
        setImageUri(prev => [...prev, ...response?.assets]);

        setModalVisible(false);
      }
    });
  };
  // console.log(imageUri,'imageguorijsjlkjflajlfjlsjf==========');
  const onClickImage = () => {
    // console.log(imageUri, 'imageguorijsjlkjflajlfjlsjf==========');
     
    if (imageUri == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'At least select one image',
      });
    } else {
      let immgarr=[]
      imageUri.forEach(element => {
        // console.log('elemenntsis', element);
        immgarr.push({
          uri: element?.uri,
          type: element?.type,
          name: element?.fileName,
        });
      })
//  console.log("immgarrimmgarr-",immgarr)

      props.navigation.navigate('AddPropertyTitle', {
        propertyId: route.params.propertyId,
        userId: route.params.userId,
        categoryId: route.params.categoryId,
        address: route.params.address,
        latitude: route.params.latitude,
        longitude: route.params.longitude,
        countryId: route.params.countryId,
        provinceId: route.params.propertyId,
        cityId: route.params.cityId,
        areaId: route.params.areaId,
        postalCode: route.params.postalCode,
        streetName: route.params.streetName,
        streetType: route.params.streetType,
        selectFloor: route.params.selectFloor,
        stairs: route.params.stairs,
        elevator: route.params.elevator,
        apertNo: route.params.apertNo,
        beds: route.params.beds,
        bedrooms: route.params.bedrooms,
        bathrooms: route.params.bathrooms,
        kitchen: route.params.kitchen,
        // amenityId: route.params.amenityId,
        maxGuest: route.params.maxGuest,
        petsAllow: route.params.petsAllow,
        numberOfNights: route.params.numberOfNights,
        cctv: route.params.cctv,
        cameraLocation: route.params.cameraLocation,
        wifiUserName: route.params.wifiUserName,
        wifiPassword: route.params.wifiPassword,
        television: route.params.television,
        televisionLocation: route.params.televisionLocation,
        allowDayBooking: route.params.allowDayBooking,
        houseRules: route.params.houseRules,
        responseTime: route.params.responseTime,
        image: immgarr,
      });
    }
  };
  return (
    <View style={commonStyles.container}>
      <Header
        props={props}
        Heading={'Add Property'}
        onPress={() => props.navigation.goBack()}
      />

      <Text
        style={{
          marginTop: 20,
          fontSize: 20,
          fontWeight: '500',
          marginHorizontal: 20,
          color: color.primaryColorBlack,
        }}>
        Upload Your Property Photos (Multiple or one)
      </Text>
      <TouchableOpacity
        //    onPress={onClose}
        onPress={() => setModalVisible(!modalVisible)}>
        <ImageBackground
          resizeMode="stretch"
          source={Images.RectangleUpload}
          style={{
            width: width - 40,
            height: 250,
            alignSelf: 'center',
            marginTop: 20,
            justifyContent: 'center',
            alignItems: 'center',
          }}>
          {/* <Image source={imageUri} style={{ width: '100%', height: 350, borderRadius: 10 }} resizeMode='cover' /> */}
          {/* {
                        imageUri == '' ?
                            <Image source={Images.uploadIcon} style={{ height: 60, width: 60, top: -135 }} /> :
                            <View></View>
                            
                        } */}
          <Image source={Images.uploadIcon} style={{ height: 60, width: 60, }} />
        </ImageBackground>

        <Modal
          visible={modalVisible}
          transparent
          onRequestClose={
            // onClose
            () => {
              Alert.alert('Modal has been closed.');
              setModalVisible(!modalVisible);
            }
          }
          style={{
            margin: 20,
            backgroundColor: 'white',
            borderRadius: 20,
            padding: 35,
            alignItems: 'center',
            shadowColor: '#000',
            shadowOffset: {
              width: 0,
              height: 2,
            },
            shadowOpacity: 0.25,
            shadowRadius: 4,
            elevation: 5,
          }}>

          <View
            style={{
              // flex: 0.34,
              justifyContent: 'center',
              alignItems: 'center',
              flexDirection: 'column',
              // height: 400,
              alignSelf:'center',
              width: 200,
              backgroundColor: 'white',
              marginTop: 200,
              // marginLeft: 100,
              borderRadius: 20,
              padding:40
            }}>
            <TouchableOpacity
              onPress={() => setModalVisible(!modalVisible)}
              style={{ alignSelf: 'flex-end', padding: 0 }}>
              <Image
                source={Images.cancelImageIcon}
                style={{ height: 25, width: 25 }}
              />
            </TouchableOpacity>
            <TouchableOpacity
              onPress={CameraAction}
              style={{
                borderWidth: 1,
                padding: 12,
                borderRadius: 20,
                // margin: 8,
                // marginLeft:20
              }}>
              <Text style={{ fontWeight: '600', color: '#000' }}>Camera</Text>
            </TouchableOpacity>


            <TouchableOpacity
              onPress={GalleryAction}
              style={{
                borderWidth: 1,
                padding: 12,
                borderRadius: 20,
                marginTop: 12
                // margin: 8,
                // marginRight:20
              }}>
              <Text style={{ fontWeight: '600', color: '#000' }}>Gallary</Text>
            </TouchableOpacity>
          </View>
        </Modal>
      </TouchableOpacity>
      <ScrollView style={{width:'100%'}}  horizontal>
      <View style={{ marginHorizontal: 20, marginTop: 20, flexDirection: 'row' }}>
        {imageUri.map((img, index) => (
          <ImageBackground
            key={index}
            source={img}
            style={{
              width: width / 5,
              height: 100,
              borderRadius: 10,
              marginHorizontal: 2,
              alignContent: 'center',
              alignItems: 'center'

            }}
            resizeMode="cover"
          >
            <TouchableOpacity onPress={() => { imageUri.splice(index), setImageUri([...imageUri]) }} style={{ alignSelf: 'flex-end', margin: 5 }}>
              <Image source={Images.xmark} style={{ height: 20, width: 20 ,tintColor:color.appOrangeColor}} />
            </TouchableOpacity>
          </ImageBackground>

        ))}

      </View>
      </ScrollView>
      {/* <View style={{ flex: 1, justifyContent: 'flex-end', marginBottom: 30 }}>
                <View style={{ backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 15, marginTop: 20 }}>
                    <TouchableOpacity onPress={() => props.navigation.navigate('AddPropertyOffer')} style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                    <TouchableOpacity onPress={() => props.navigation.navigate('AddPropertyTitle')}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={[styles.linearGradient, { borderRadius: 10 }]}>
                            <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                Next
                            </Text>
                        </LinearGradient>
                    </TouchableOpacity>
                </View>
            </View> */}
      <View style={{  justifyContent: 'flex-end' }}>
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
            <TouchableOpacity onPress={() => onClickImage()}>
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
});

export default AddPropertyPhotos;
