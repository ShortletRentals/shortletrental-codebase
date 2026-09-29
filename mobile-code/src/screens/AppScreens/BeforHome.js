import React, {useState, useEffect} from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  StyleSheet,
  Alert,
  BackHandler,
  TextInput,
  TouchableOpacity,
  _Text,
  ScrollView,
  StatusBar,
} from 'react-native';
import {color, height, width} from '../../styles/colors';
import Images from '../../styles/Images';
import {commonStyles} from './../../styles/style';
import {Colors, colors} from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import {SafeAreaView} from 'react-native-safe-area-context';
import {Context as AuthContext} from '../../context/AuthContext';
import {useContext} from 'react';

const BeforHome = props => {
  const renderData = [
    {
      id: 1,
      image: Images.calenderIcon,
      title: 'Find Your Perfect Shortlet',
      fontStyle: {
        color: color.appOrangeColor,
      },
      screenName: 'BottomTab',
      from: 'findHome',
    },
    // {
    //   id: 2,
    //   image: Images.checkMarkIcon,
    //   title: 'Guest Login',
    //   fontStyle: {
    //     color: color.appBlueColor,
    //   },
    //   screenName: 'Logins',
    //   from: 'logins',
    // },
    // {
    //   id: 3,
    //   image: Images.youngBoyIcon,
    //   title: 'Become a Host',
    //   fontStyle: {
    //     color: color.appOrangeColor,
    //   },
    //   // screenName: 'BottomTab'
    //   screenName: 'BecomeHost',
    //   from: 'become',
    // },
  ];
  // const {checkActiveUser} = useContext(AuthContext)

  useEffect(() => {
      const backAction = () => {
          Alert.alert('Hold on!', 'Are you sure you want to Exit App?', [
              {
                  text: 'Cancel',
                  onPress: () => null,
                  style: 'cancel',
              },
              { text: 'YES', onPress: () => BackHandler.exitApp() },
          ]);
          return true;
      };

      const backHandler = BackHandler.addEventListener(
          'hardwareBackPress',
          backAction,
      );

      return () => backHandler.remove();
  }, [props])
  return (
    <View style={[commonStyles.container]}>
      <ImageBackground
        source={Images.beforeHome}
        style={{
          flex: 1,
          
        
         justifyContent: 'flex-end',
         
         }}>
        {renderData.map((item, index) => {
          return (
            <TouchableOpacity
              style={{}}
              key={item.id}
              onPress={() =>
                props.navigation.reset({
                  index: 0,
                  routeNames: [item.screenName],
                  routes: [{name: item.screenName}],
                })
              }
              //    props.navigation.navigate(item.screenName, { form: item.from })}
            >
              <View
                style={{
                  marginTop: 20,
                  borderRadius: 15,
                  borderColor: color.appYellowColor,
                  width: width - 40,
                  backgroundColor: color.appTextBackgoundColor,
                  flexDirection: 'row',
                  height: 80,
                  alignItems: 'center',
                  alignSelf: 'center',
                }}>
                <View
                  style={{
                    width: width * (60 / 375),
                    height: width * (60 / 375),
                    justifyContent: 'center',
                    alignItems: 'center',
                    backgroundColor: color.appYellowColor,
                    borderRadius: 40,
                    marginLeft: width * (20 / 375),
                  }}>
                  <Image
                    source={item?.image}
                    style={{
                      width: width * (30 / 375),
                      height: width * (30 / 375),
                    }}
                  />
                </View>
                <Text
                  style={[
                    {
                      fontSize: width * (15 / 375),
                      color: color.appYellowColor,
                      width: width * (200 / 375),
                      alignSelf: 'center',
                      marginLeft: 10,
                    },
                    item.fontStyle,
                  ]}>
                  {item?.title}
                </Text>
              </View>
            </TouchableOpacity>
          );
        })}
        <TouchableOpacity
          onPress={() => props.navigation.navigate('Logins')}
          //    props.navigation.navigate(item.screenName, { form: item.from })}
        >
          <View
            style={{
              marginTop: 20,
              borderRadius: 15,
              borderColor: color.appYellowColor,
              width: width - 40,
              backgroundColor: color.appTextBackgoundColor,
              flexDirection: 'row',
              height: 80,
              alignItems: 'center',
              alignSelf: 'center',
            }}>
            <View
              style={{
                width: width * (60 / 375),
                height: width * (60 / 375),
                justifyContent: 'center',
                alignItems: 'center',
                backgroundColor: color.appYellowColor,
                borderRadius: 40,
                marginLeft: width * (20 / 375),
              }}>
              <Image
                source={Images.checkMarkIcon}
                style={{
                  width: width * (30 / 375),
                  height: width * (30 / 375),
                }}
              />
            </View>
            <Text
              style={[
                {
                  fontSize: width * (15 / 375),
                  color: color.appYellowColor,
                  width: width * (200 / 375),
                  alignSelf: 'center',
                  marginLeft: 10,
                  color: color.appOrangeColor,
                },
              ]}>
              Guest Login
            </Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity
          onPress={() => props.navigation.navigate('BecomeHost')}
          //    props.navigation.navigate(item.screenName, { form: item.from })}
        >
          <View
            style={{
              marginTop: 20,
              borderRadius: 15,
              borderColor: color.appYellowColor,
              width: width - 40,
              backgroundColor: color.appTextBackgoundColor,
              flexDirection: 'row',
              height: 80,
              alignItems: 'center',
              alignSelf: 'center',
            }}>
            <View
              style={{
                width: width * (60 / 375),
                height: width * (60 / 375),
                justifyContent: 'center',
                alignItems: 'center',
                backgroundColor: color.appYellowColor,
                borderRadius: 40,
                marginLeft: width * (20 / 375),
              }}>
              <Image
                source={Images.youngBoyIcon}
                style={{
                  width: width * (30 / 375),
                  height: width * (30 / 375),
                }}
              />
            </View>
            <Text
              style={[
                {
                  fontSize: width * (15 / 375),
                  color: color.appYellowColor,
                  width: width * (200 / 375),
                  alignSelf: 'center',
                  marginLeft: 10,
                  color: color.appOrangeColor,
                },
              ]}>
              Become a Host
            </Text>
          </View>
        </TouchableOpacity>
        {/* <TouchableOpacity onPress={() => props.navigation.navigate("Logins")}>
                    <View style={{ borderColor: color.appOrangeColor, backgroundColor: color.appLightOrangeColor, width: width * (120 / 375), height: width * (40 / 375), alignSelf: 'center', marginBottom: 20, marginTop: 20, borderRadius: 4, borderWidth: 1, borderColor: color.appOrangeColor, justifyContent: 'center' }}>
                        <Text style={{ color: color.appOrangeColor, fontSize: 15, alignSelf: 'center' }}>Skip to Home</Text>
                    </View>
                </TouchableOpacity> */}
        <View style={{marginBottom: 50}} />
      </ImageBackground>
    </View>
  );
};
export default BeforHome;
