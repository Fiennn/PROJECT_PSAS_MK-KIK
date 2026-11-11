package com.homestaygo.app

import retrofit2.Call
import retrofit2.http.GET
import retrofit2.http.Path

interface HomestayApi {
    @GET("api/homestay")
    fun getHomestays(): Call<HomestayResponse>

    @GET("api/homestay/{id}")
    fun getHomestayDetail(@Path("id") id: Int): Call<HomestayDetailResponse>
}
