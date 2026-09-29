import axios from 'axios';
import React, {useState, useRef, useEffect} from 'react';
import {useContext} from 'react';
import {
  View,
  Text,
  ImageBackground,
  Image,
  TouchableOpacity,
  Animated,
  InteractionManager,
  Platform,
} from 'react-native';
import {color, width} from '../styles/colors';
import PushNotification from 'react-native-push-notification';
import Images from '../styles/Images';
import {height} from '../styles/style';
import {Context as AuthContext} from '../context/AuthContext';
import {Notification_Count} from '../network/Webconstant';
import {useFocusEffect, useNavigationState} from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Toast } from 'react-native-toast-message/lib/src/Toast';

const CustomTabBar = ({props}) => {
  const [curIndex, setCurIndex] = useState(0);
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const screenArr = ['Home', 'Myfavorites', 'Account', '', ''];

  const [badge, setBadge] = useState(0);
  const myAnim = index => {
    var obj = {
      toValue: index * (width / 5) + 8,
      velocity: 10,
      useNativeDriver: true,
    };
    Animated.spring(fadeAnim, obj).start();
  };
  const {
    checkActiveUser,
    state: {Token_ID, USER_ID, USERDATA},
  } = useContext(AuthContext);



  useEffect(()=>{
    getNotificationApi();

  },[props])

  
  
  PushNotification.configure({ 
    // (optional) Called when Token is generated (iOS and Android)
    

    // (required) Called when a remote is received or opened, or local notification is opened
    onNotification: function (notification) {
      console.log(notification.foreground);
      console.log('NOTIFICATION tab bar', notification);
      Toast.show({ type: 'success', 
      text1: notification?.notification?.title,
      text2:notification?.notification?.body });

      getNotificationApi();
    },

    popInitialNotification: true,
    requestPermissions: true,
  });

  const getNotificationApi = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_Count,
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {
        if (response.data.status == true) {
          console.log('=========response badge', response.data);
          setBadge(response.data.data);
        }
      });
    } catch (error) {
      // console.log('errorNotification', error);
    }
  };

  global.foo = 4;

  useEffect(() => {
    myAnim(0);
  }, [props]);

  const routes = useNavigationState(state => state.routes);
  const currentRoute = routes[routes.length - 1].name;

  console.log('currentRoute: =====================', routes[0]?.state?.index); // use for change the tinit color of icon
  useFocusEffect(
    React.useCallback(() => {
      const task = InteractionManager.runAfterInteractions(() => {
        if (routes[0]?.state?.index == 0) {
          setCurIndex(0);
        }
      });
      return () => task.cancel();
    }, [routes[0]?.state?.index]),
  );

  
  // useEffect(() => {

  // }, [routes[0]?.state?.index])
  return (
    <View
      style={{
        height:Platform.OS == 'ios'? 60 : 54, //height / 10 - 10,
        width: width,
        backgroundColor: 'white',
      }}>
      <ImageBackground
        source={Images.footer}
        style={{
          height: 54,
          width: width, //61,
          // marginTop: 10
        }}>
        <Animated.View
          style={{
            width: 54, //60,
            height: 3,
            backgroundColor: color.appWhiteColor,
            transform: [{translateX: fadeAnim}],
            borderRadius: 10,
          }}
        />
        <View
          style={{
            flexDirection: 'row',
            alignItems: 'center',
            justifyContent: 'space-between',
            paddingVertical: 10,
            paddingHorizontal: 20,
          }}>
          <TouchableOpacity
            onPress={() => {
              setCurIndex(0);
              myAnim(0);
              props?.navigation?.navigate('Home');
            }}
            style={{
              alignItems: 'center',
              zIndex: 1000,
              backgroundColor: '#fff',
              height: 30,
              width: 30,
              justifyContent: 'center',
              // marginBottom: 20
            }}>
            {curIndex == 0 ? (
              <Image
                source={Images.OrangeIcons}
                style={{height: 18.75, width: 18.75}}
              />
            ) : (
              <Image
                source={Images.SearchIcons}
                style={{height: 18.75, width: 18.75}}
              />
            )}
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => {
              setCurIndex(1);
              myAnim(1);
              props?.navigation?.navigate('Myfavorites');
            }}
            style={{
              alignItems: 'center',
              zIndex: 1000,
              backgroundColor: '#fff',
              height: 30,
              width: 30,
              justifyContent: 'center',
              // marginBottom: 20
            }}>
            {curIndex == 1 ? (
              <Image
                source={Images.heart}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            ) : (
              <Image
                source={Images.unFavoriteIcon}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            )}
          </TouchableOpacity>

          <View
            style={{
              position: 'absolute',
              left: 0,
              right: 0,
              top: -45, //-60,
              alignItems: 'center',
            }}>
            <View>
              <TouchableOpacity
                activeOpacity={0.8}
                onPress={() => {
                  setCurIndex(2);
                  myAnim(2);
                  props?.navigation?.navigate('MyBooking');
                }}
                style={{
                  padding: 0,
                  borderRadius: 70,
                }}>
                <Image
                  source={Images.EyeIcons}
                  style={{height: 88, width: 88, resizeMode: 'contain'}}
                />
              </TouchableOpacity>
            </View>
          </View>

          <View
            style={{
              width: 40,
            }}
          />

          <TouchableOpacity
            onPress={() => {
              setCurIndex(3);
              myAnim(3);
              props?.navigation?.navigate('Notification');
            }}
            style={{
              alignItems: 'center',
              marginBottom: badge >= 1 ? -22 : 1,
              backgroundColor: '#fff',
              height: 30,
              width: 30,
              justifyContent: 'center',
            }}>
            {curIndex == 3 ? (
              <Image
                source={Images.notification}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            ) : (
              <Image
                source={Images.notificationUnselectedIcon}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            )}
            {badge >= 1 && (
              <View
                style={{
                  // flex:1,

                  backgroundColor: 'red',
                  height: 24,
                  width: 24,
                  justifyContent: 'center',
                  alignItems: 'center',
                  borderRadius: 15,
                  top: -30,
                  right: -10,
                  borderWidth: 1,
                  borderColor: '#e9e9e9',
                  padding: 1,
                }}>
                <Text style={{color: '#fff'}}>{badge}</Text>
              </View>
            )}
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => {
              setCurIndex(4);
              myAnim(4), props?.navigation?.navigate('Account');
            }}
            style={{
              alignItems: 'center',
              backgroundColor: '#fff',
              height: 30,
              width: 30,
              justifyContent: 'center',
              // marginBottom: 20,
            }}>
            {curIndex == 4 ? (
              <Image
                source={Images.pfofile}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            ) : (
              <Image
                source={Images.ProfileIcons}
                style={{height: 18.75, width: 18.75}}
                resizeMode="contain"
              />
            )}
          </TouchableOpacity>
        </View>
      </ImageBackground>
    </View>
  );
};

export default CustomTabBar;

// function MyTabBar({ state, descriptors, navigation }) {
//   return (
//     <View style={{ flexDirection: 'row',backgroundColor:"#F4AF5F",height:50,borderRadius:50,justifyContent:"center",alignItems:"center" }}>
//       {state.routes.map((route, index) => {
//         const { options } = descriptors[route.key];
//         const label =
//           options.tabBarLabel !== undefined
//             ? options.tabBarLabel
//             : options.title !== undefined
//             ? options.title
//             : route.name;

//         const isFocused = state.index === index;

//         const onPress = () => {
//           const event = navigation.emit({
//             type: 'tabPress',
//             target: route.key,
//           });

//           if (!isFocused && !event.defaultPrevented) {
//             navigation.navigate(route.name);
//           }
//         };

//         const onLongPress = () => {
//           navigation.emit({
//             type: 'tabLongPress',
//             target: route.key,
//           });
//         };

//         return (
//           <TouchableOpacity
//             accessibilityRole="button"
//             accessibilityStates={isFocused ? ['selected'] : []}
//             accessibilityLabel={options.tabBarAccessibilityLabel}
//             testID={options.tabBarTestID}
//             onPress={onPress}
//             onLongPress={onLongPress}
//             style={{ flex: 1, alignItems:"center" }}
//           >
//             <Text style={{ color: isFocused ? '#673ab7' : '#222' }}>
//               {/* {label}  */}
//               Home
//             </Text>
//           </TouchableOpacity>
//         );
//       })}
//     </View>
//   );
// }

// export default MyTabBar
