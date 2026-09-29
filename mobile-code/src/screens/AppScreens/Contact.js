import {
  Image,
  KeyboardAvoidingView,
  Linking,
  SafeAreaView,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import React from 'react';
import Header from '../../components/Header';
import { color } from '../../styles/colors';
import TextView from '../../components/TextView';
import { useState } from 'react';
import LinearGradient from 'react-native-linear-gradient';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { customToast } from '../../components/ToastMessage';
import Images from '../../styles/Images';
import { useFocusEffect, useNavigation, useRoute } from '@react-navigation/native';
import axios from 'axios';
import { Contact_Url, MAIN_URL } from '../../network/Webconstant';
import { BackHandler } from 'react-native';
import { useEffect } from 'react';
import { KeyboardAwareView } from 'react-native-keyboard-aware-view';

const Contact = (props) => {
  const [state, setState] = useState({
    firstName: '',
    lastName: '',
    city: '',
    state: '',
    mobileNumber: '',
    email: '',
    textArea: '',
  });

  const navigation = useNavigation();
  const [select, setSelect] = useState(false);
  const [selected, setSelected] = useState(false);

  const handler = () => {
    props.navigation.navigate('Setting', { notification: '' })
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

  const onClickHandler = () => {
    if (state.firstName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter first name',
      });
    } else if (state.lastName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter last name',
      });
    } else if (state.city == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter city',
      });
    } else if (state.state == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter  state',
      });
    } else if (state.mobileNumber == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter mobile number',
      });
    } else if (state.email == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'please enter Email',
      });
    } else if (state.email.trim() !== '') {
      let reg = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
      if (reg.test(state.email) === false) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Invalid Email',
        });
      } else if (state.textArea == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'please enter your message',
        });
      } else if (select == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'please accept terms and conditions',
        });
      } else if (selected == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'please accept commercial information',
        });
      } else {
        onClickContactHandlerApi();
      }
    }
  };

  const onClickContactHandlerApi = async () => {
    try {
      await axios({
        method: 'post',
        url: Contact_Url,
        data: {
          first_name: state.firstName,
          last_name: state.lastName,
          email: state.email,
          city: state.city,
          province: state.state,
          mobile_number: state.mobileNumber,
          message: state.textArea,
        },
      }).then(function (res) {
        if (res.data.status == true) {
          props.navigation.navigate('Setting', { notification: '' })
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: res.data.message,
          });
          setState('')
          setSelect(false)
          setSelected(false)
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };
  return (
    <View style={{ flex: 1, backgroundColor: '#fff' }}>
      <Header
        Heading={'Contact Us'}
        onPress={() => props.navigation?.navigate('Setting', { notification: '' })}
      />
      <KeyboardAwareView>
      <ScrollView style={{ flex:1}}>
        <Text
          style={{
            fontSize: 20, marginTop: 20,
            marginHorizontal: 20,
            color: color.primaryColorBlack,
            fontWeight: '600',
          }}>
          Contact Us
        </Text>
        <TextView
          placeholder={'First Name'}
          value={state.firstName}
          onChangeText={text => setState({ ...state, firstName: text })}
        />
        <TextView
          placeholder={'Last Name'}
          value={state.lastName}
          onChangeText={text => setState({ ...state, lastName: text })}
        />
        <TextView
          placeholder={'City'}
          value={state.city}
          onChangeText={text => setState({ ...state, city: text })}
        />
        <TextView
          placeholder={'State'}
          value={state.state}
          onChangeText={text => setState({ ...state, state: text })}
        />
        <TextView
          placeholder={'Mobile Number'}
          value={state.mobileNumber}
          onChangeText={text =>
            setState({
              ...state,
              mobileNumber: text.replace(
                /[^A-Za-z0-9-@A-Za-z0-9.A-Za-z0-9]/g,
                '',
              ),
            })
          }
          isNumeric
          maxLength={10}
        />
        <TextView
          placeholder={'Email'}
          value={state.email}
          onChangeText={text =>
            setState({
              ...state,
              email: text.replace(/[^A-Za-z0-9-@A-Za-z0-9.A-Za-z0-9]/g, ''),
            })
          }
        />
        <TextInput
          placeholder="Your Message"
          placeholderTextColor={color.appTextColor}

          textAlignVertical="top"
          multiline={true}
          value={state.textArea}
          onChangeText={text =>
            setState({
              ...state,
              textArea: text,
            })
          }
          style={{
            paddingLeft: 15,
            height: 160,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,
            color: '#000'
          }}
        />
        <View
          style={{ marginHorizontal: 20, flexDirection: 'row', marginTop: 5 }}>
          <TouchableOpacity onPress={() => setSelect(!select)}>
            {select ? (
              <Image
                source={Images.checkfull}
                style={{ height: 24, width: 24 }}
              />
            ) : (
              <Image
                source={Images.checkblank}
                style={{ height: 24, width: 24 }}
              />
            )}
          </TouchableOpacity>
          <Text style={{ marginHorizontal: 16, color: '#000' }}>
            I have read and accepted the
            <Text style={{color:color.appBlueColor,fontWeight:"600"}} onPress={() => Linking.openURL(`${MAIN_URL}/privacy-policy`)} > Privacy Policy </Text> 

              and general conditions
          </Text>
        </View>
        <View
          style={{ marginHorizontal: 20, flexDirection: 'row', marginTop: 10 }}>
          <TouchableOpacity onPress={() => setSelected(!selected)}>
            {selected ? (
              <Image
                source={Images.checkfull}
                style={{ height: 24, width: 24 }}
              />
            ) : (
              <Image
                source={Images.checkblank}
                style={{ height: 24, width: 24 }}
              />
            )}
          </TouchableOpacity>
          <Text style={{ marginHorizontal: 16, color: '#000' }}>
            Acceptence to recieve commercial information
          </Text>
        </View>
        <TouchableOpacity style={{marginBottom:20,marginTop:10}} onPress={() => onClickHandler()}>
          <LinearGradient
            colors={[color.appYellowColor, color.appOrangeColor]}
            style={{
              margin: 10,
              marginHorizontal: 20,
              height: 40,
              width: 100,
              backgroundColor: 'orange',
              justifyContent: 'center',
              alignItems: 'center',
              borderWidth: 1,
              borderRadius: 20,
              borderColor: '#e9e9e9',
            }}>
            <Text style={{ color: color.white, fontSize: 20 }}>Send</Text>
          </LinearGradient>
        </TouchableOpacity>
      </ScrollView>
      </KeyboardAwareView>
    </View>
  );
};

export default Contact;

const styles = StyleSheet.create({});
