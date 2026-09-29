import React, {useState} from 'react';
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
} from 'react-native';
import {color, height, width} from '../../styles/colors';
import Images from '../../styles/Images';
import {commonStyles} from './../../styles/style';
import {Colors, colors} from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import {SafeAreaView} from 'react-native-safe-area-context';
import { BackHandler } from 'react-native';
import { useEffect } from 'react';


const BecomeHost = props => {
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
  return (
    <View style={commonStyles.container}>
      <Header
        props={props}
        Heading={'Become a Host'}
        onPress={() => props.navigation.goBack()}
      />
      <ScrollView showsVerticalScrollIndicator={false}>
          <ImageBackground source={Images.slider} style={{resizeMode:'contain',height:200,width:width}}>
            <Text style={{textAlign:'center',color:'white',fontSize:20,marginTop:90}}>Start your hosting journey with us</Text>
            </ImageBackground>
        <View style={{marginHorizontal: 20, marginTop: 10}}>
          <View style={{flexDirection:'row',justifyContent:'space-between',alignItems:'center'}}>
          <Image source={Images.hostimg} style={{height:100,width:130}}/>
         <View>
          <Text
            style={{
              fontSize: 20,
              fontWeight: '600',
              color: color.primaryColorBlack,
            }}>
            Hosts
          </Text>
          <Text style={{fontSize: 15, fontWeight: '400', marginTop: 10,color:'#000',width:width/2,textAlign:'justify'}}>
            Thank you for your interest in becoming a host on the Short-let
            Rentals platform. Shortlet Rentals is an online booking platform
            created for owners of shortlet properties and lovers of shortlet
            living
          </Text>
          </View>
          </View>
          <View
            style={{
              flexDirection: 'row',
              backgroundColor: color.appTextBackgoundColor,
              height: 50,
              justifyContent: 'space-between',
              borderRadius: 10,
              marginTop: 10,
            }}>
            <View style={{marginLeft: 20, justifyContent: 'center'}}>
              <Text
                style={{
                  fontSize: 14,
                  fontWeight: '400',
                  color: color.primaryColorBlack,
                }}>
                Click here to
              </Text>
            </View>
            <TouchableOpacity
              onPress={() =>

                //  props.navigation.navigate('AddPropertyPhotos')
                 props.navigation.navigate('BecomeaHost')
                }
              style={{
                borderRadius: 4,
                backgroundColor: color.appOrangeColor,
                justifyContent: 'center',
                margin: 7.5,

                height:35,
                width:'40%'
              }}>
              <Text
                style={{
                  // marginHorizontal: 20,
                  color: color.appWhiteColor,
                  fontWeight: '400',
                  fontSize:14,
                  textAlign:'center'
                }}>
                Get Started
              </Text>
            </TouchableOpacity>
          </View>
          <Text style={{fontSize: 14, fontWeight: '400', marginTop: 10,color:'#000'}}>
            When you are done completing form, schedule your in-person
            appointment.
          </Text>
          <View
            style={{
              flexDirection: 'row',
              backgroundColor: color.appTextBackgoundColor,
              height: 50,
              justifyContent: 'space-between',
              borderRadius: 10,
              marginTop: 10,
            }}>
            <View style={{marginLeft: 20, justifyContent: 'center'}}>
              <Text
                style={{
                  fontSize: 14,
                  fontWeight: '400',
                  color: color.primaryColorBlack,
                }}>
                Click here to
              </Text>
            </View>
            <TouchableOpacity
              onPress={() =>
                props.navigation.navigate('AddPropertyScheduleDate')
                // props.navigation.navigate('AddPropertyLocated')
              }
              style={{
                borderRadius: 4,
                backgroundColor: color.appOrangeColor,
                justifyContent: 'center',
                marginHorizontal: 10,
                marginVertical: 4,
              }}>
              <Text
                style={{
                  marginHorizontal: 20,
                  color: color.appWhiteColor,
                  fontWeight: '400',
                }}>
                Schedule Appointment
              </Text>
            </TouchableOpacity>
          </View>
          <Text style={{fontSize: 14, fontWeight: '400', marginTop: 10,color:'#000'}}>
            Thank you for wanting to be a part of a community that makes it
            easier for guest to find their perfect Shortlet.
          </Text>
          <View
            style={{
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              marginTop: 10,
            }}>
            <View style={{marginHorizontal: 20, marginVertical: 20}}>
              <Image source={Images.BecomeIcon} style={{alignSelf: 'center'}} />
              <Text
                style={{
                  fontSize: 18,
                  marginTop: 10,
                  fontWeight: '600',
                  alignSelf: 'center',
                  textAlign: 'center',
                  color: color.primaryColorBlack,
                }}>
                Become a host - Earn more money when you list with Shortlet
                Rentals
              </Text>
              <Text
                style={{
                  fontSize: 14,
                  fontWeight: '400',
                  marginTop: 6,
                  textAlign: 'center',color:'#000'
                }}>
                Shortlet Rentals hosts earn more money and get more exposure
                when they list their property on the platform
              </Text>
            </View>
          </View>
        </View>
      </ScrollView>
      <TouchableOpacity
        onPress={() => props.navigation.navigate('BecomeaHost')}
        style={{
          marginTop: 20,
          backgroundColor: color.appOrangeColor,
          width: '90%',
          padding: 10,
          borderRadius: 10,
          justifyContent: 'center',
          alignSelf: 'center',
          height: 50,
          marginBottom: 20,
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
          Get Started
        </Text>
      </TouchableOpacity>
    </View>
  );
};

export default BecomeHost;
