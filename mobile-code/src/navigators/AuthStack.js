import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import Login from '../screens/AuthScreens/Login';
import SignUp from '../screens/AuthScreens/SignUp';
import Otp from '../screens/AuthScreens/Otp';
import BeforHome from '../screens/AppScreens/BeforHome';
import Logins from '../screens/AuthScreens/Logins';
import Search from '../screens/AppScreens/Search'; 
import Booking from '../screens/AppScreens/Booking';
import AddPersonalData from '../screens/AppScreens/AddPersonalData';
import Offers from '../screens/AppScreens/Offers';
import AddGuestData from '../screens/AppScreens/AddGuestData';
import Payment from '../screens/AppScreens/Payment';
import LoyaltyPoints from '../screens/AppScreens/LoyaltyPoints';
import ChangePassword from '../screens/AppScreens/ChangePassword';
import Myfavorites from '../screens/AppScreens/MyFavorites';
import Home from '../screens/AppScreens/Home';
import BottomTab from './BottomTab';
import HomeDetails from '../screens/AppScreens/HotelDetails';
import Setting from '../screens/AppScreens/Settings';
import EditProfile from '../screens/AppScreens/EditProfile';
import Filter from '../screens/AppScreens/Filter';
import MyBooking from '../screens/AppScreens/MyBooking';
import BookingDetails from '../screens/AppScreens/BoookingDetails';
import BookingFill from '../screens/AppScreens/BookingFill';
import MyCards from '../screens/AppScreens/MyCards';
import Account from '../screens/AppScreens/Account';
import OfferDetail from '../screens/AppScreens/OfferDetail'
import Chat from '../screens/AppScreens/Chat';
import Notification from '../screens/AppScreens/Notification';
import BecomeaHost from '../screens/AppScreens/BecomeaHost';
import AboutUs from '../screens/AppScreens/AboutUs';
import Photos from '../screens/AppScreens/Photos';
import PhotosView from '../screens/AppScreens/PhotosView';
import BecomeHost from '../screens/AppScreens/BecomeHost';
import AddPropertyguest from '../screens/AppScreens/AddPropertyGuest';
// import AddPropertyLocated from '../screens/AppScreens/AddPropertyLocated';
import AddPropertyWelcome from '../screens/AppScreens/AddPropertyWelcome';
import AddPropertyPhotos from '../screens/AppScreens/AddPropertyPhotos';
import SearchProperty from '../screens/AppScreens/SearchProperty'
import AddPropertyPlace from '../screens/AppScreens/AddPropertyPlace';
import AddPropertyOffer from '../screens/AppScreens/AddPropertyOffer';
import AddPropertyNight from '../screens/AppScreens/AddPropertyNight';
import AddPropertyTitle from '../screens/AppScreens/AddPropertyTitle';
import AddPropertyDescription from '../screens/AppScreens/AddPropertyDescription';
import AddPropertyLocated from '../screens/AppScreens/AddPropertyLocated';
import AddPropertyguest1 from '../screens/AppScreens/AddPropertyGuest1';
// import BecomeHostHomeScreen from '../screens/AppScreens/BecomeHostHomeScreen';
import SplashScreen from '../screens/AppScreens/SplashScreen';
import { ActivityIndicator, View } from 'react-native';
import { useState ,useEffect} from 'react';
import ChatMessage from '../screens/AppScreens/ChatMessage';
import { ImagePicker } from '../screens/AppScreens/ImagePicker';
import AddPropertySchedule from '../screens/AppScreens/AddPropertySchedule';
import AddPropertyScheduleDate from '../screens/AppScreens/AddPropertyScheduleDate';
import AddPropertyScheduleDetails from '../screens/AppScreens/AddPropertyScheduleDetails';
import MapScreen from '../screens/AppScreens/MapScreen';
import ResetPassword from '../screens/AppScreens/ResetPassword';
import AddPropertyLocated1 from '../screens/AppScreens/AddPropertyLocated1';
import Contact from '../screens/AppScreens/Contact';
import Help from '../screens/AppScreens/Help';
import OtpHost from '../screens/AuthScreens/OtpHost';
import ReviewScreen from '../screens/AppScreens/ReviewScreen';
import HomeView from '../screens/AppScreens/HomeView';
import OffersHome from '../screens/AppScreens/OffersHome';
import ThankYou from '../screens/AppScreens/ThankYou';
import OrderScreen from '../screens/AppScreens/OrderScreen';
import MyReservation from '../screens/AppScreens/MyReservation';
// import BottomTab2 from './BottomTab2';
const Stack = createStackNavigator();

const AuthStack = () => {
  const [loader,setLoader] = useState(true)
  const [splash,setSplash] = useState(false)
  
useEffect(()=>{
  setTimeout(()=>{
    setLoader(false)
  },3000)
},[])

  return (
    // <NavigationContainer>
    
      // <Stack.Navigator initialRouteName='Splash' defaultScreenOptions={{ gestureEnabled: true }} screenOptions={{ headerMode: false }}>
     <>
      {/*   <Stack.Navigator initialRouteName='BeforHome' defaultScreenOptions={{ gestureEnabled: true }} screenOptions={{ headerMode: false }}>   */}
     {loader ? <SplashScreen/> : 
     
     <Stack.Navigator initialRouteName='BottomTab' defaultScreenOptions={{ gestureEnabled: true }} screenOptions={{ headerMode: false }}> 
        <Stack.Screen name="BookingFill" component={BookingFill} /> 
        <Stack.Screen name='Splash' component={SplashScreen} />
        <Stack.Screen name="Login" component={Login} /> 
        <Stack.Screen name="OtpHost" component={OtpHost} />
        <Stack.Screen name="BookingDetails" component={BookingDetails} /> 
        <Stack.Screen name="MyBooking" component={MyBooking} /> 
         <Stack.Screen name="Filter" component={Filter} />
        <Stack.Screen name="Logins" component={Logins} />
        <Stack.Screen name="SignUp" component={SignUp} />
        <Stack.Screen name="BeforHome" component={BeforHome} />
        <Stack.Screen name="ChangePassword" component={ChangePassword} />
        <Stack.Screen name="Myfavorites" component={Myfavorites} />
        <Stack.Screen name="Otp" component={Otp} />
        <Stack.Screen name="BottomTab" component={BottomTab} />
        <Stack.Screen name="Setting" component={Setting} />
        <Stack.Screen name="Search" component={Search} />
        <Stack.Screen name="HomeDetails" component={HomeDetails} />
        <Stack.Screen name="Booking" component={Booking} />
        <Stack.Screen name="AddPersonalData" component={AddPersonalData} />
        <Stack.Screen name="AddGuestData" component={AddGuestData} />
        <Stack.Screen name="Payment" component={Payment} />
        <Stack.Screen name="EditProfile" component={EditProfile} />
        <Stack.Screen name="LoyaltyPoints" component={LoyaltyPoints} />
        <Stack.Screen name="Offers" component={Offers} />
        <Stack.Screen name="MyCards" component={MyCards} />
        <Stack.Screen name="Account" component={Account} />
        <Stack.Screen name="Notification" component={Notification} />
        <Stack.Screen name="OfferDetail" component={OfferDetail} />
        <Stack.Screen name="Chat" component={Chat} />
        <Stack.Screen name="ChatMessage" component={ChatMessage} />
        <Stack.Screen name="BecomeaHost" component={BecomeaHost} />
        {/* <Stack.Screen name="BecomeaHostHome" component={BecomeHostHomeScreen} /> */}
        <Stack.Screen name="AboutUs" component={AboutUs} />
        <Stack.Screen name="Photos" component={Photos} />
        <Stack.Screen name="PhotosView" component={PhotosView} />
        <Stack.Screen name="BecomeHost" component={BecomeHost} />
        <Stack.Screen name="AddPropertyguest" component={AddPropertyguest} />
        <Stack.Screen name="AddPropertyPhotos" component={AddPropertyPhotos} />
        <Stack.Screen name="AddProperty" component={AddPropertyguest1} />
        <Stack.Screen name="AddPropertyLocated" component={AddPropertyLocated} />
        <Stack.Screen name="AddPropertyWelcome" component={AddPropertyWelcome} />
        <Stack.Screen name="SearchProperty" component={SearchProperty} />
        <Stack.Screen name="AddPropertyPlace" component={AddPropertyPlace} />
        <Stack.Screen name="AddPropertyOffer" component={AddPropertyOffer} />
        <Stack.Screen name="AddPropertyNight" component={AddPropertyNight} />
        <Stack.Screen name="AddPropertyTitle" component={AddPropertyTitle} />
        <Stack.Screen name="AddPropertyDescription" component={AddPropertyDescription}/>
        <Stack.Screen name="AddPropertySchedule" component={AddPropertySchedule}/>
        <Stack.Screen name="AddPropertyScheduleDate" component={AddPropertyScheduleDate}/>
        <Stack.Screen name="AddPropertyScheduleDetails" component={AddPropertyScheduleDetails}/>
        <Stack.Screen name="ResetPassword" component={ResetPassword}/>
        <Stack.Screen name="AddPropertyLocated1" component={AddPropertyLocated1}/>
        <Stack.Screen name="Contact" component={Contact}/>
        <Stack.Screen name="Help" component={Help}/>
        <Stack.Screen name="Review" component={ReviewScreen} />
        <Stack.Screen name="Homeview" component={HomeView} />
        <Stack.Screen name="OfferHome" component={OffersHome} />
        <Stack.Screen name="ThankYou" component={ThankYou} />
        <Stack.Screen name="OrderScreen" component={OrderScreen} />
        <Stack.Screen name="MyReservation" component={MyReservation} />

        {/* <Stack.Screen name="Home" component={Home}/> */}
              </Stack.Navigator>

  }
              </>
    // </NavigationContainer>
  );
};

export default AuthStack;
