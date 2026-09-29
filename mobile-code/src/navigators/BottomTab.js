import React, { useContext, useEffect, useState } from 'react';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';

import Booking from '../screens/AppScreens/Booking';
import Offers from '../screens/AppScreens/Offers';
import Payment from '../screens/AppScreens/Payment';
import Myfavorites from '../screens/AppScreens/MyFavorites';
import Account from '../screens/AppScreens/Account';
import ChangePassword from '../screens/AppScreens/ChangePassword';
import Setting from '../screens/AppScreens/Settings';
import Login from '../screens/AuthScreens/Login';
import Home from '../screens/AppScreens/Home';
import { ImageComponent } from 'react-native';
import Images from '../styles/Images';
import CustomTabBar from './CustomTabBar';
import Search from '../screens/AppScreens/Search';
import MyBooking from '../screens/AppScreens/MyBooking';
import Notification from '../screens/AppScreens/Notification';
import MyCards from '../screens/AppScreens/MyCards';
import LoyaltyPoints from '../screens/AppScreens/LoyaltyPoints';
import HomeDetails from '../screens/AppScreens/HotelDetails';
import BookingDetails from '../screens/AppScreens/BoookingDetails';
import AddPersonalData from '../screens/AppScreens/AddPersonalData';
import AddGuestData from '../screens/AppScreens/AddGuestData';
import OfferDetail from '../screens/AppScreens/OfferDetail';
import Chat from '../screens/AppScreens/Chat';
import BecomeaHost from '../screens/AppScreens/BecomeaHost';
import AboutUs from '../screens/AppScreens/AboutUs';
import Photos from '../screens/AppScreens/Photos';
import PhotosView from '../screens/AppScreens/PhotosView';
// import BecomeHostHomeScreen from '../screens/AppScreens/BecomeHostHomeScreen';
import ChatMessage from '../screens/AppScreens/ChatMessage';
import axios from 'axios';
import { Notification_Count } from '../network/Webconstant';
// import Favorite from '../screens/AppScreens/MyFavorites';
import { Context as AuthContext } from '../context/AuthContext';
const Tab = createBottomTabNavigator();
import { useNavigation, useNavigationState, useRoute } from '@react-navigation/native';
import MyTabBar from './CustomTabBar';
import HomeView from '../screens/AppScreens/HomeView';



function BottomTab() {
  const route = useRoute();
  // console.log(route.name,"----------------------------");



  return (
    <Tab.Navigator
    
      screenOptions={{ headerShown: false, }}
      backBehavior={"history"} 
      tabBar={(props, index) => <CustomTabBar props={props}
      //  hhkjhkj={Tab.Screen.name} 
       />}
      // tabBar={props => <MyTabBar {...props} />}
      >
      

      <Tab.Screen
        name="Home"
        component={Home}
        options={{ headerShown: false }}
        />
      {/* <Tab.Screen name="BecomeHostHomeScreen" component={BecomeHostHomeScreen} options={{ headerShown: false }} /> */}

     
      <Tab.Screen
        name="Myfavorites"
        component={Myfavorites}
        options={{ headerShown: false }}
      />
      {/* <Tab.Screen
        name="Cart"
        component={Booking}
        options={{ headerShown: false }}
      /> */}
      <Tab.Screen
        name="Account"
        component={Account}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="Search"
        component={Search}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="MyBooking"
        component={MyBooking}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="Notification"
        component={Notification}
        options={{
          headerShown: false,
          // tabBarBadge:1,
          // tabBarBadgeStyle:'red'
          //  () => {
          //   return (
          //     <View style={{backgrounColor:'red'}}>
          //       <Text style={{color:'black'}}>1</Text>
          //     </View>
          //   );
          // },
        }}
      />
      <Tab.Screen
        name="Setting"
        component={Setting}
        options={{ headerShown: false }}
      />

      <Tab.Screen
        name="MyCards"
        component={MyCards}
        options={{ headerShown: false }}
      />

      <Tab.Screen
        name="ChangePassword"
        component={ChangePassword}
        options={{ headerShown: false }}
      />

      <Tab.Screen
        name="LoyaltyPoints"
        component={LoyaltyPoints}
        options={{ headerShown: false }}
      />

         <Tab.Screen
        name="Offers"
        component={Offers}
        options={{ headerShown: false }}
      />

      <Tab.Screen
        name="Booking"
        component={Booking}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="HomeDetails"
        component={HomeDetails}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="BookingDetails"
        component={BookingDetails}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="Payment"
        component={Payment}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="AddPersonalData"
        component={AddPersonalData}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="AddGuestData"
        component={AddGuestData}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="OfferDetail"
        component={OfferDetail}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="BecomeaHost"
        component={BecomeaHost}
        options={{ headerShown: false }}
      />
      <Tab.Screen name="Chat" component={Chat} options={{ headerShown: false }} />
      <Tab.Screen
        name="AboutUs"
        component={AboutUs}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="Photos"
        component={Photos}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="PhotosView"
        component={PhotosView}
        options={{ headerShown: false }}
      />
      <Tab.Screen
        name="Homeview"
        component={HomeView}
        options={{ headerShown: false }}
      />
      {/* <Tab.Screen
        name="Homeview"
        component={}
        options={{ headerShown: false }}
      /> */}

    </Tab.Navigator>
  );
}
export default BottomTab;