import { ActivityIndicator, FlatList, InteractionManager, Modal, SafeAreaView, StyleSheet, Text, TouchableOpacity, View, Image } from 'react-native'
import React, { useContext, useEffect, useState } from 'react'
import { useFocusEffect, useRoute } from '@react-navigation/native'
import axios from 'axios'
import { add_To_Favourite, HOME_URL, PROPERTYLIST } from '../../network/Webconstant'
import { Context as HomeContext } from '../../context/HomeContext'
import { Context as AuthContext } from '../../context/AuthContext'
// import Image from 'react-native-fast-image'
import AsyncStorage from '@react-native-async-storage/async-storage'
import { width } from '../../styles/style'
import CustomImageFlatList from '../../components/CustomImageFlatList'
import { color } from '../../styles/colors'
import Images from '../../styles/Images'
import Header from '../../components/Header'
import { SliderShimmer } from '../../components/Skeleton'
import { Toast } from 'react-native-toast-message/lib/src/Toast'
import { BackHandler } from 'react-native'

let myToken = ''

const HomeView = (props) => {
  const [homeData, setHomeData] = useState([])
  const [select1, setSelect1] = useState(true)
  const [pageNo, setPageNo] = useState(1)
  const [isListEnd, setListEnd] = useState(false);
  const [bottomLoading, setBottomLoading] = useState(false);
  const [loader, setLoader] = useState(false)
  const route = useRoute()
  const [modalVisible, setModalVisible] = useState(false);
  const [review, setReview] = useState(route.params.REVIEW)

  useFocusEffect(
    React.useCallback(() => {
      console.log('params',
        route.params.BEDROOMS,
        route.params.PROVINCEID,
        route.params.CITYID,
        route.params.CHECKIN,
        route.params.CHECKOUT,
        route.params.TYPE,
        route.params.SUPERHOST,
        route.params.LUXURY,
        route.params.RARE,
        route.params.REVIEW,
        route.params.category,
        route.params.featured,
        route.params.services,
        route.params.bathroom,
        route.params.MAX_GUEST
      )
      const task = InteractionManager.runAfterInteractions(async () => {
        // console.log('-------------homeview--------------');
        const Localtoken = await AsyncStorage.getItem('token_id');
        myToken = Localtoken
        if (Localtoken == null) {
          // console.log('-------------homeview if--------------');
          getData()
        } else {
          // console.log('-------------homeview- else-------------');
          getData()
        }
      })

      return () => task.cancel();
    }, [props]),
  );

  const handler = () => {
    props.navigation.navigate('Home')
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

  const getData = async () => {
    setHomeData([])
    setLoader(true)
    // setData([])
    setListEnd(false);
    setPageNo(1)
    homePageApi(false)
    // checkActiveUser().then(res => {
    //   if (res) {
    //     homePageApi(false)
    //   } else {
    //     homePageApi(false)
    //   }
    // })
  }

  useEffect(() => {
    if (pageNo > 1 && !isListEnd && !bottomLoading) {
      console.log('current page no is' + pageNo, isListEnd)
      homePageApi(true)
    }
  }, [pageNo])

  console.log('-----route.params', route.params.TYPE, route.params.SUPERHOST, review)
  const homePageApi = async (isLoadMore) => {
    const tempPageNo = isLoadMore ? pageNo : 1
    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');
    setLoader(true)
    let body = {
      page: tempPageNo,
      user_id: USER_ID,
      // province_id: route?.params?.ID == route?.params?.ID ? route?.params?.ID : '',
      type: route?.params?.TYPE !== undefined ? route?.params?.TYPE : '',
      is_super_host: route?.params?.SUPERHOST !== undefined ? route?.params?.SUPERHOST : '',
      luxury: route?.params?.LUXURY !== undefined ? route?.params?.LUXURY : '',
      rare: route?.params?.RARE !== undefined ? route?.params?.RARE : '',
      rating: route.params.REVIEW !== undefined ? route.params.REVIEW : '',
      province_id: route.params.PROVINCEID !== undefined ? route.params.PROVINCEID : '',
      city_id: route.params.CITYID !== undefined ? route.params.CITYID : '',
      max_guest: route.params.MAX_GUEST !== undefined ? route.params.MAX_GUEST : '',

      checkIn: route.params.CHECKIN !== undefined || route.params.CHECKIN !== 'Select Date' || route.params.CHECKIN !== 'Invalid date' ? route.params.CHECKIN : '',
      checkOut: route.params.CHECKOUT !== undefined || route.params.CHECKOUT !== 'Select Date' || route.params.CHECKOUT !== 'Invalid date' ? route.params.CHECKOUT : '',
      no_of_bedrooms: route.params.BEDROOMS !== undefined ? route.params.BEDROOMS : '',
      category: route.params.category !== undefined ? route.params.category : '',
      bathroom: route.params.bathroom !== undefined ? route.params.bathroom : '',
      services: route.params.services !== undefined ? route.params.services : '',
      featured: route.params.featured !== undefined ? route.params.featured : '',
      area_id: route.params.AREAID !== undefined ? route.params.AREAID : ''
    }
    console.log("---------------body-=-=-=-=-=-", body)
    try {
      setLoader(tempPageNo === 1)
      setBottomLoading(tempPageNo > 1)
      await axios({
        method: 'post',
        url: PROPERTYLIST,
        data: body,
        headers: { "Authorization": `Bearer ${Localtoken}`, "Accept": "application/json" }
      }).then((response) => {
        // console.log(pageNo,'home api response', response.data.data);
        if (response.data.status == true) {
          setListEnd(!!!response?.data?.data?.length || response?.data?.data?.length < 10)
          setPageNo(tempPageNo)
          let dataArr = response.data.data
          if ( pageNo == 1) {
            setHomeData(response.data.data)
          } else {
            //   !isLoadMore && setData(response.data.data.category.data)
              setHomeData([...homeData, ...response.data.data]) 
              
          }
          //   isLoadMore ? setData([...data, ...response.data.data.category.data]) : setData(response.data.data.category.data ?? [])
          // console.log("data is ", response.data.data.properties.data[0].is_fav)
          setLoader(false)
          setBottomLoading(false);
        }
        else {
          setBottomLoading(false);

          setLoader(false)
          // setHomeData(response.data.data)
        }
      })
    } catch (error) {
      console.log('errorHomeApi', error)
      setLoader(false)
      setBottomLoading(false);
    }
  }

  const addFavouriteApi = async (item) => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');
    // setLoader(true)
    try {
      axios({
        method: 'post',
        url: add_To_Favourite,
        data: { userId: USER_ID, product_id: item },
        headers: { "Accept": "application/json", "Authorization": `Bearer ${Localtoken}` }
      }).then(
        function (response) {
          setModalVisible(true)

          if (response.data.status == true) {

            Toast.show({
              type: 'success',
              text1: "Shortlet",
              text2: response.data.message

            })
            setModalVisible(false)

            // setLoader(false)
            // homePageApiNew(false)
          } else {

            Toast.show({
              type: 'error',
              text1: "Shortlet",
              text2: response.data.message

            })
            setModalVisible(false)
          }
          setHomeData(

            homeData.map((prop) => {
              if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
              return prop;

            }),
          )
        }
      )
    } catch (error) {
      console.log('error is add to favorite home', error);
    }


  }
  const addtoFavoriteHandler = async (item) => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    // console.log('=======token add', Localtoken);
    if (Localtoken == null) {
      Alert.alert('Hold on!', 'Please login first?', [
        {
          text: 'Cancel',
          onPress: () => null,
          style: 'cancel',
        },
        { text: 'Login', onPress: () => props?.navigation?.navigate('Logins') },
      ]);
    } else {

      addFavouriteApi(item),
        setModalVisible(true)

    }
  }
  const renderItem1 = ({ item, index }) => {
    console.log('item is  fav', item?.featured,"f",item?.luxury,"l",item.rare,);
    return (
      <View
        style={{ marginTop: 20 }}
        activeOpacity={0.5}
        key={item.id}>
        {/* //{ */}
        {/* // item.book_type == route?.params?.Instant ? */}

        <View>
          <View style={{
            // paddingHorizontal: width * (10 / 375),
            // marginBottom: width * (4 / 375),
            borderColor: '#E9E9E9',
            borderBottomLeftRadius: 10,
            borderBottomRightRadius: 10,
            width: width * (332 / 375),
            borderTopRightRadius: 10,
            borderTopLeftRadius: 10
          }}>
            {
              item?.featured == 'Yes' ?
                <Image source={Images.featureImage} style={{ height: 53, width: 94, marginBottom: -53, zIndex: 1000 }} /> : null
            }
            {
              item?.rare == 'Yes' ?
                <Image source={Images.raretag} style={{ height: 53, width: 94, marginBottom: -53, zIndex: 1000 }} /> : null
            }
            {
              item?.luxury == 'Yes' ?
                <Image source={Images.luxurytag} style={{ height: 53, width: 94, marginBottom: -53, zIndex: 1000 }} /> : null
            }
            {
              item?.get_user_web?.is_super_host == 'Yes' ?
                <Image source={Images.supertag} style={{ height: 53, width: 94, marginBottom: -53, zIndex: 1000 }} /> : null
            }

            <CustomImageFlatList
              data={item.get_property_images.length > 0 ? item?.get_property_images?.map(img => img.image) :
                ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                  "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
            />

            <TouchableOpacity
              onPress={
                async () => {
                  props.navigation.navigate('HomeDetails', { propertyid: item.id, price: item.price }),
                    await AsyncStorage.setItem('Price', JSON.stringify(item.price))
                }
              }
              style={{
                paddingHorizontal: width * (10 / 375),
                marginBottom: width * (1 / 375),
                borderColor: '#E9E9E9',
                borderWidth: 1,
                // marginBottom: 10,
                borderBottomLeftRadius: 10,
                borderBottomRightRadius: 10,
                // marginHorizontal: width * (20 / 375),
                backgroundColor: '#FFFFFF',
                width: width * (332 / 375),
                // height: width * (145 / 375)
                // height: 151
              }}>

              <Text numberOfLines={1} style={{ fontSize: 19, fontWeight: '400', color: color.primaryColorBlack }}>{item.title}</Text>
              <View style={{ flexDirection: "row", justifyContent: 'space-between', width: width * (300 / 375) }}>
                <View style={{ flexDirection: "row", width: width * (145 / 375), marginTop: 5, justifyContent: 'center', alignItems: 'center', marginHorizontal: 20 }}>
                  <TouchableOpacity style={{}} >
                    <Image resizeMode="contain" source={Images.LocationIcons} style={{
                      // paddingLeft: 10,
                      width: 20, height: 20, alignSelf: 'center'
                    }} />
                  </TouchableOpacity>
                  <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                    <Text numberOfLines={2} style={{ color: '#393939', marginHorizontal: 12 }}>

                      {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}

                    </Text>
                  </View >
                </View >
                <View style={{ flexDirection: "row", width: width * (70 / 375), marginTop: 10, justifyContent: 'flex-end', alignItems: 'center' }}>
                  <TouchableOpacity>
                    <Image resizeMode="contain"
                      source={Images.StartIcon} style={{
                        paddingLeft: 10,
                        width: 16, height: 16
                      }} />
                  </TouchableOpacity>
                  <Text style={{ color: color.primaryColorBlack }}> {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})</Text>

                </View >
              </View >
              <View style={{ flexDirection: 'row' }}>
                <Text style={{ marginTop: 4, fontWeight: '500', color: color.primaryColorBlack }}> NGN {item.price}</Text>
                <Text style={{ marginTop: 4, color: '#393939', marginHorizontal: 12 }}>Night</Text>
              </View >

              <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 10 }}>
                <View style={{ flexDirection: "row", width: width * (130 / 375), height: 40, marginTop: 4, justifyContent: 'space-between' }}>
                  <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                    <TouchableOpacity>
                      <Image resizeMode="contain"
                        source={Images.GroupIcons}
                        // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                        style={{
                          paddingLeft: 10,
                          width: 20, height: 20
                        }} />
                    </TouchableOpacity>
                    <TouchableOpacity>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>{item.max_guest}</Text>
                    </TouchableOpacity>
                  </View >

                  <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                    <TouchableOpacity>
                      <Image resizeMode="contain" source={Images.BedIcons} style={{
                        paddingLeft: 10,
                        width: 20, height: 20
                      }} />
                    </TouchableOpacity>
                    <TouchableOpacity>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>{item?.get_property_bedroom?.map((bed) => bed.no_of_bedrooms)}</Text>
                    </TouchableOpacity>

                  </View >

                </View >
                <View style={{ backgroundColor: color.appBlueColor, height: 40, width: 40, borderRadius: 20, alignItems: 'center', justifyContent: 'center' }}>
                  <Image resizeMode="contain" source={Images.SeekLogoIcons}
                    style={{
                      width: 20, height: 20, justifyContent: 'center', alignContent: 'center'
                    }} />
                </View>
              </View >

            </TouchableOpacity >
          </View >
          <View style={{ alignSelf: 'flex-end', marginHorizontal: 10, zIndex: 1000, position: 'absolute', right: 5, marginTop: 10 }}
          >


            <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
              <Image source={item.is_fav === 1 ? Images.heart : Images.heartIcon} style={{ height: 24, width: 24 }} resizeMode='contain' />
            </TouchableOpacity>
          </View >
        </View >
        {/* //  :  */}
        {/* // <View> */}
        {/* //   <View style={{ */}

        {/* //     borderColor: '#E9E9E9',
            //     borderBottomLeftRadius: 10,
            //     borderBottomRightRadius: 10,
            //     width: width * (332 / 375),
            //     borderTopRightRadius: 10,
            //     borderTopLeftRadius: 10
            //   }}>
            //     { */}
        {/* //       item?.featured == 'Yes' ? */}

        {/* //         <Image source={Images.featureImage} style={{ height: 53, width: 94, marginBottom: -53, zIndex: 1000 }} /> : null} */}
        {/* <CustomImageFlatList
                  data={item.get_property_images.length > 0 ? item?.get_property_images?.map(img => img.image) :
                    ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                      "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
                /> */}

        {/* <TouchableOpacity
                  onPress={
                    async () => {
                      props.navigation.navigate('HomeDetails', { propertyid: item.id, price: item.price }),
                        await AsyncStorage.setItem('Price', JSON.stringify(item.price))
                    }
                  }
                  style={{
                    paddingHorizontal: width * (10 / 375),
                    marginBottom: width * (1 / 375),
                    borderColor: '#E9E9E9',
                    borderWidth: 1,
                    // marginBottom: 10,
                    borderBottomLeftRadius: 10,
                    borderBottomRightRadius: 10,
                    // marginHorizontal: width * (20 / 375),
                    backgroundColor: '#FFFFFF',
                    width: width * (332 / 375),
                    // height: width * (145 / 375)
                    // height: 151
                  }}>

                  <Text numberOfLines={1} style={{ fontSize: 19, fontWeight: '400', color: color.primaryColorBlack }}>{item.title}</Text>
                  <View style={{ flexDirection: "row", justifyContent: 'space-between', width: width * (300 / 375) }}>
                    <View style={{ flexDirection: "row", width: width * (145 / 375), marginTop: 5, justifyContent: 'center', alignItems: 'center', marginHorizontal: 20 }}>
                      <TouchableOpacity style={{}} >
                        <Image resizeMode="contain" source={Images.LocationIcons} style={{
                          // paddingLeft: 10,
                          width: 20, height: 20, alignSelf: 'center'
                        }} />
                      </TouchableOpacity>
                      <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                        <Text numberOfLines={2} style={{ color: '#393939', marginHorizontal: 12 }}>
                          {/* {item?.get_property_address?.map((add) => add.address)} */}
        {/* {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}
                          

                          </Text>
                      </View >
                    </View > */}
        {/* <View style={{ flexDirection: "row", width: width * (70 / 375), marginTop: 10, justifyContent: 'flex-end', alignItems: 'center' }}>
                      <TouchableOpacity>
                        <Image resizeMode="contain"
                          source={Images.StartIcon} style={{
                            paddingLeft: 10,
                            width: 16, height: 16
                          }} />
                      </TouchableOpacity>
                      <Text style={{ color: color.primaryColorBlack }}> {item.avg_rating == null ? 0 : item.avg_rating}</Text>

                    </View >
                  </View >
                  <View style={{ flexDirection: 'row' }}>
                    <Text style={{ marginTop: 4, fontWeight: '500', color: color.primaryColorBlack }}> NGN {item.price}</Text>
                    <Text style={{ marginTop: 4, color: '#393939', marginHorizontal: 12 }}>night</Text>
                  </View > */}

        {/* <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 10 }}>
                    <View style={{ flexDirection: "row", width: width * (130 / 375), height: 40, marginTop: 4, justifyContent: 'space-between' }}>
                      <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                        {/* <TouchableOpacity>
                          <Image resizeMode="contain"
                            source={Images.GroupIcons}
                            // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                            style={{
                              paddingLeft: 10,
                              width: 20, height: 20
                            }} />
                        </TouchableOpacity> */}
        {/* <TouchableOpacity>
                          <Text style={{ paddingLeft: 10, color: '#000' }}>{item.max_guest}</Text>
                        </TouchableOpacity>
                      </View >

                      <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (8 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
                        <TouchableOpacity>
                          <Image resizeMode="contain" source={Images.BedIcons} style={{
                            paddingLeft: 10,
                            width: 20, height: 20
                          }} />
                        </TouchableOpacity> */}
        {/* <TouchableOpacity>
                          <Text style={{ paddingLeft: 10, color: '#000' }}>{item?.get_property_bedroom?.map((bed) => bed.no_of_bedrooms)}</Text>
                        </TouchableOpacity>
                      </View >
                    </View >
                    <View style={{ backgroundColor: color.appBlueColor, height: 40, width: 40, borderRadius: 20, alignItems: 'center', justifyContent: 'center' }}>
                      <Image resizeMode="contain" source={Images.SeekLogoIcons}
                        style={{
                          width: 20, height: 20, justifyContent: 'center', alignContent: 'center'
                        }} />
                    </View>
                  </View >

                </TouchableOpacity > */}
        {/* </View > */}
        {/* <View style={{ alignSelf: 'flex-end', marginHorizontal: 10, zIndex: 1000, position: 'absolute', right: 5, marginTop: 10 }}
              >
                <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
                  <Image source={item.is_fav === 1 ? Images.heart : Images.heartIcon} style={{ height: 24, width: 24 }} resizeMode='contain' />
                </TouchableOpacity>
              </View> */}
        {/* </View > */}
        {/* } */}

      </View>
    )
  }
  return (
    <View style={{ flex: 1, }}>
      <Header Heading={'Showing Results'} onPress={() => props?.navigation?.navigate('Home')} />
      {
        loader ? <SliderShimmer /> :

          <View style={{ flex: 1, marginHorizontal: 20, }}>
            {
              homeData == '' ? <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
                <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />
                <Text style={{ fontSize: 20, alignSelf: 'center', color: '#000', fontWeight: '400', marginTop: 20, }}>No Data Found</Text>
              </View> :

                <FlatList
                  data={homeData}
                  nestedScrollEnabled={true}
                  renderItem={renderItem1}
                  onEndReached={() => {
                    if (!isListEnd) {
                      setPageNo(pageNo + 1)
                    }
                  }}
                  onEndReachedThreshold={.5}
                  keyExtractor={(_, index) => index.toString()}
                  showsVerticalScrollIndicator={false}
                  ListFooterComponent={bottomLoading && <View style={{ height: 50, width: '100%' }}>
                    <ActivityIndicator size={'small'} color='black' />
                  </View>}

                />
            }
          </View>
      }
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
            marginTop: "70%",
            backgroundColor: 'rgba(0, 0, 0,0.6)',
            marginHorizontal: "40%"
          }}>
          <ActivityIndicator size="large" color="#F99428" />

        </View>
      </Modal>
      <View style={{ marginBottom: 0 }} />
    </View>
  )
}

export default HomeView

