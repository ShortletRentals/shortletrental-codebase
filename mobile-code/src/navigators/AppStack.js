import React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import Logins from '../screens/AuthScreens/Logins';
import SignUp from '../screens/AuthScreens/SignUp';
import Otp from '../screens/AuthScreens/Otp';
import ResetPassword from '../screens/AppScreens/ResetPassword';
import BottomTab from './BottomTab';
import BeforHome from '../screens/AppScreens/BeforHome';
import EditProfile from '../screens/AppScreens/EditProfile';
import SplashScreen from '../screens/AppScreens/SplashScreen';
import { useEffect } from 'react';
import { useNavigation } from '@react-navigation/native';
import { BackHandler } from 'react-native';
import Help from '../screens/AppScreens/Help';
import Contact from '../screens/AppScreens/Contact';
import AddPropertyScheduleDetails from '../screens/AppScreens/AddPropertyScheduleDetails';
import AddPropertyScheduleDate from '../screens/AppScreens/AddPropertyScheduleDate';
import AddPropertySchedule from '../screens/AppScreens/AddPropertySchedule';
import AddPropertyDescription from '../screens/AppScreens/AddPropertyDescription';
import AddPropertyTitle from '../screens/AppScreens/AddPropertyTitle';
import AddPropertyNight from '../screens/AppScreens/AddPropertyNight';
import AddPropertyOffer from '../screens/AppScreens/AddPropertyOffer';
import AddPropertyPlace from '../screens/AppScreens/AddPropertyPlace';
import SearchProperty from '../screens/AppScreens/SearchProperty';
import AddPropertyWelcome from '../screens/AppScreens/AddPropertyWelcome';
import AddPropertyLocated from '../screens/AppScreens/AddPropertyLocated';
import AddPropertyguest1 from '../screens/AppScreens/AddPropertyGuest1';
import AddPropertyPhotos from '../screens/AppScreens/AddPropertyPhotos';
import AddPropertyguest from '../screens/AppScreens/AddPropertyGuest';
import BecomeHost from '../screens/AppScreens/BecomeHost';
import PhotosView from '../screens/AppScreens/PhotosView';
import Photos from '../screens/AppScreens/Photos';
import AboutUs from '../screens/AppScreens/AboutUs';
// import BecomeHostHomeScreen from '../screens/AppScreens/BecomeHostHomeScreen';
import AddPropertyLocated1 from '../screens/AppScreens/AddPropertyLocated1';
import BecomeaHost from '../screens/AppScreens/BecomeaHost';
import Chat from '../screens/AppScreens/Chat';
import ChatMessage from '../screens/AppScreens/ChatMessage';
import OtpHost from '../screens/AuthScreens/OtpHost';
// import Review from '../screens/AppScreens/ReviewScreen';
import ReviewScreen from '../screens/AppScreens/ReviewScreen';
import HomeView from '../screens/AppScreens/HomeView';
import OffersHome from '../screens/AppScreens/OffersHome';
import ThankYou from '../screens/AppScreens/ThankYou';
import OrderScreen from '../screens/AppScreens/OrderScreen';
import MyReservation from '../screens/AppScreens/MyReservation';

const Stack = createStackNavigator();

const AppStack = () => {

    return (
        <Stack.Navigator initialRouteName='BeforHome' defaultScreenOptions={{ gestureEnabled: true }} screenOptions={{ headerMode: false }} >
            {/* <Stack.Screen name="Splash" component={SplashScreen} /> */}
            <Stack.Screen name="Logins" component={Logins} />
            <Stack.Screen name="SignUp" component={SignUp} />
            <Stack.Screen name="Otp" component={Otp} />
            <Stack.Screen name="OtpHost" component={OtpHost} />
            <Stack.Screen name="ResetPassword" component={ResetPassword} />
            <Stack.Screen name="BottomTab" component={BottomTab} />
            <Stack.Screen name="BeforHome" component={BeforHome} />
            <Stack.Screen name="EditProfile" component={EditProfile} />
            {/* <Stack.Screen name="BecomeaHostHome" component={BecomeHostHomeScreen} /> */}
            <Stack.Screen name="AboutUs" component={AboutUs} />
            <Stack.Screen name="Photos" component={Photos} />
            <Stack.Screen name="PhotosView" component={PhotosView} />
            <Stack.Screen name="BecomeaHost" component={BecomeaHost} />
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
            <Stack.Screen name="AddPropertyDescription" component={AddPropertyDescription} />
            <Stack.Screen name="AddPropertySchedule" component={AddPropertySchedule} />
            <Stack.Screen name="AddPropertyScheduleDate" component={AddPropertyScheduleDate} />
            <Stack.Screen name="AddPropertyScheduleDetails" component={AddPropertyScheduleDetails} />
            <Stack.Screen name="AddPropertyLocated1" component={AddPropertyLocated1} />
            <Stack.Screen name="Contact" component={Contact} />
            <Stack.Screen name="Help" component={Help} />
            <Stack.Screen name="Chat" component={Chat} />
            <Stack.Screen name="ChatMessage" component={ChatMessage} />
            <Stack.Screen name="Review" component={ReviewScreen} />
            <Stack.Screen name="Homeview" component={HomeView} />
            <Stack.Screen name="OfferHome" component={OffersHome} />
            <Stack.Screen name="ThankYou" component={ThankYou} />
            <Stack.Screen name="OrderScreen" component={OrderScreen} />
        <Stack.Screen name="MyReservation" component={MyReservation} />



        </Stack.Navigator>
    );
};

export default AppStack;
